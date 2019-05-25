<?php

namespace omnilight\scheduling;

use Cron\CronExpression;
use GuzzleHttp\Client as HttpClient;
use Symfony\Component\Process\Process;
use yii\base\Application;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\mail\MailerInterface;
use yii\mutex\Mutex;


/**
 * Class Event
 */
class Event extends Component
{
    const EVENT_BEFORE_RUN = 'beforeRun';
    const EVENT_AFTER_RUN = 'afterRun';

    /**
     * Command string
     * @var string
     */
    public $command;
    /**
     * The cron expression representing the event's frequency.
     *
     * @var string
     */
    protected $_expression = '* * * * * *';
    /**
     * The timezone the date should be evaluated on.
     *
     * @var \DateTimeZone|string
     */
    protected $_timezone;
    /**
     * The user the command should run as.
     *
     * @var string
     */
    protected $_user;
    /**
     * The filter callback.
     *
     * @var \Closure
     */
    protected $_filter;
    /**
     * The reject callback.
     *
     * @var \Closure
     */
    protected $_reject;
    /**
     * The location that output should be sent to.
     *
     * @var string
     */
    protected $_output = null;
    /**
     * The string for redirection.
     *
     * @var array
     */
    protected $_redirect = ' > ';
    /**
     * The array of callbacks to be run after the event is finished.
     *
     * @var array
     */
    protected $_afterCallbacks = [];
    /**
     * The human readable description of the event.
     *
     * @var string
     */
    protected $_description;
    /**
     * The mutex implementation.
     *
     * @var \yii\mutex\Mutex
     */
    protected $_mutex;

    /**
     * Create a new event instance.
     *
     * @param Mutex $mutex
     * @param string $command
     * @param array $config
     */
    public function __construct(Mutex $mutex, $command, $config = [])
    {
        $this->command = $command;
        $this->_mutex = $mutex;
        $this->_output = $this->getDefaultOutput();
        parent::__construct($config);
    }

    /**
     * Run the given event.
     * @param Application $app
     */
    public function run(Application $app)
    {
        $this->trigger(self::EVENT_BEFORE_RUN);
        if (count($this->_afterCallbacks) > 0) {
            $this->runCommandInForeground($app);
        } else {
            $this->runCommandInBackground($app);
        }
        $this->trigger(self::EVENT_AFTER_RUN);
    }

    /**
     * Get the mutex name for the scheduled command.
     *
     * @return string
     */
    protected function mutexName()
    {
        return 'framework/schedule-' . sha1($this->_expression . $this->command);
    }

    /**
     * Run the command in the foreground.
     *
     * @param Application $app
     */
    protected function runCommandInForeground(Application $app)
    {
        (new Process(
            trim($this->buildCommand(), '& '), dirname($app->request->getScriptFile()), null, null, null
        ))->run();
        $this->callAfterCallbacks($app);
    }

    /**
     * Build the comand string.
     *
     * @return string
     */
    public function buildCommand()
    {
        $command = $this->command . $this->_redirect . $this->_output . ' 2>&1 &';
        return $this->_user ? 'sudo -u ' . $this->_user . ' ' . $command : $command;
    }

    /**
     * Call all of the "after" callbacks for the event.
     *
     * @param Application $app
     */
    protected function callAfterCallbacks(Application $app)
    {
        foreach ($this->_afterCallbacks as $callback) {
            call_user_func($callback, $app);
        }
    }

    /**
     * Run the command in the background using exec.
     *
     * @param Application $app
     */
    protected function runCommandInBackground(Application $app)
    {
        chdir(dirname($app->request->getScriptFile()));
        exec($this->buildCommand());
    }

    /**
     * Determine if the given event should run based on the Cron expression.
     *
     * @param Application $app
     * @return bool
     */
    public function isDue(Application $app)
    {
        return $this->expressionPasses() && $this->filtersPass($app);
    }

    /**
     * Determine if the Cron expression passes.
     *
     * @return bool
     */
    protected function expressionPasses()
    {
        $date = new \DateTime('now');
        if ($this->_timezone) {
            $date->setTimezone($this->_timezone);
        }
        return CronExpression::factory($this->_expression)->isDue($date);
    }

    /**
     * Determine if the filters pass for the event.
     *
     * @param Application $app
     * @return bool
     */
    protected function filtersPass(Application $app)
    {
        if ($this->_filter && !call_user_func($this->_filter, $app) ||
            $this->_reject && call_user_func($this->_reject, $app)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Schedule the event to run hourly.
     *
     * @return $this
     */
    public function hourly()
    {
        return $this->cron('0 * * * * *');
    }

    /**
     * The Cron expression representing the event's frequency.
     *
     * @param  string $expression
     * @return $this
     */
    public function cron($expression)
    {
        $this->_expression = $expression;
        return $this;
    }

    /**
     * Schedule the event to run daily.
     *
     * @return $this
     */
    public function daily()
    {
        return $this->cron('0 0 * * * *');
    }

    /**
     * Schedule the command at a given time.
     *
     * @param  string $time
     * @return $this
     */
    public function at($time)
    {
        return $this->dailyAt($time);
    }

    /**
     * Schedule the event to run daily at a given time (10:00, 19:30, etc).
     *
     * @param  string $time
     * @return $this
     */
    public function dailyAt($time)
    {
        $segments = explode(':', $time);
        return $this->spliceIntoPosition(2, (int)$segments[0])
            ->spliceIntoPosition(1, count($segments) == 2 ? (int)$segments[1] : '0');
    }

    /**
     * Splice the given value into the given position of the expression.
     *
     * @param  int $position
     * @param  string $value
     * @return Event
     */
    protected function spliceIntoPosition($position, $value)
    {
        $segments = explode(' ', $this->_expression);
        $segments[$position - 1] = $value;
        return $this->cron(implode(' ', $segments));
    }

    /**
     * Schedule the event to run twice daily.
     *
     * @return $this
     */
    public function twiceDaily()
    {
        return $this->cron('0 1,13 * * * *');
    }

    /**
     * Schedule the event to run only on weekdays.
     *
     * @return $this
     */
    public function weekdays()
    {
        return $this->spliceIntoPosition(5, '1-5');
    }

    /**
     * Schedule the event to run only on Mondays.
     *
     * @return $this
     */
    public function mondays()
    {
        return $this->days(1);
    }

    /**
     * Set the days of the week the command should run on.
     *
     * @param  array|int $days
     * @return $this
     */
    public function days($days)
    {
        $days = is_array($days) ? $days : func_get_args();
        return $this->spliceIntoPosition(5, implode(',', $days));
    }

    /**
     * Schedule the event to run only on Tuesdays.
     *
     * @return $this
     */
    public function tuesdays()
    {
        return $this->days(2);
    }

    /**
     * Schedule the event to run only on Wednesdays.
     *
     * @return $this
     */
    public function wednesdays()
    {
        return $this->days(3);
    }

    /**
     * Schedule the event to run only on Thursdays.
     *
     * @return $this
     */
    public function thursdays()
    {
        return $this->days(4);
    }

    /**
     * Schedule the event to run only on Fridays.
     *
     * @return $this
     */
    public function fridays()
    {
        return $this->days(5);
    }

    /**
     * Schedule the event to run only on Saturdays.
     *
     * @return $this
     */
    public function saturdays()
    {
        return $this->days(6);
    }

    /**
     * Schedule the event to run only on Sundays.
     *
     * @return $this
     */
    public function sundays()
    {
        return $this->days(0);
    }

    /**
     * Schedule the event to run weekly.
     *
     * @return $this
     */
    public function weekly()
    {
        return $this->cron('0 0 * * 0 *');
    }

    /**
     * Schedule the event to run weekly on a given day and time.
     *
     * @param  int $day
     * @param  string $time
     * @return $this
     */
    public function weeklyOn($day, $time = '0:0')
    {
        $this->dailyAt($time);
        return $this->spliceIntoPosition(5, $day);
    }

    /**
     * Schedule the event to run monthly.
     *
     * @return $this
     */
    public function monthly()
    {
        return $this->cron('0 0 1 * * *');
    }

    /**
     * Schedule the event to run yearly.
     *
     * @return $this
     */
    public function yearly()
    {
        return $this->cron('0 0 1 1 * *');
    }

    /**
     * Schedule the event to run every minute.
     *
     * @return $this
     */
    public function everyMinute()
    {
        return $this->cron('* * * * * *');
    }

    /**
     * Schedule the event to run every N minutes.
     *
     * @param int|string $minutes
     * @return $this
     */
    public function everyNMinutes($minutes)
    {
        return $this->cron('*/'.$minutes.' * * * * *');
    }

    /**
     * Schedule the event to run every five minutes.
     *
     * @return $this
     */
    public function everyFiveMinutes()
    {
        return $this->everyNMinutes(5);
    }

    /**
     * Schedule the event to run every ten minutes.
     *
     * @return $this
     */
    public function everyTenMinutes()
    {
        return $this->everyNMinutes(10);
    }

    /**
     * Schedule the event to run every thirty minutes.
     *
     * @return $this
     */
    public function everyThirtyMinutes()
    {
        return $this->cron('0,30 * * * * *');
    }

    /**
     * Set the timezone the date should be evaluated on.
     *
     * @param  \DateTimeZone|string $timezone
     * @return $this
     */
    public function timezone($timezone)
    {
        $this->_timezone = $timezone;
        return $this;
    }

    /**
     * Set which user the command should run as.
     *
     * @param  string $user
     * @return $this
     */
    public function user($user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * Do not allow the event to overlap each other.
     *
     * @return $this
     */
    public function withoutOverlapping()
    {
        return $this->then(function() {
            $this->_mutex->release($this->mutexName());
        })->skip(function() {
            return !$this->_mutex->acquire($this->mutexName());
        });
    }

    /**
     * Register a callback to further filter the schedule.
     *
     * @param  \Closure $callback
     * @return $this
     */
    public function when(\Closure $callback)
    {
        $this->_filter = $callback;
        return $this;
    }

    /**
     * Register a callback to further filter the schedule.
     *
     * @param  \Closure $callback
     * @return $this
     */
    public function skip(\Closure $callback)
    {
        $this->_reject = $callback;
        return $this;
    }

    /**
     * Send the output of the command to a given location.
     *
     * @param  string $location
     * @return $this
     */
    public function sendOutputTo($location)
    {
        $this->_redirect = ' > ';
        $this->_output = $location;
        return $this;
    }

    /**
     * Append the output of the command to a given location.
     *
     * @param  string $location
     * @return $this
     */
    public function appendOutputTo($location)
    {
        $this->_redirect = ' >> ';
        $this->_output = $location;
        return $this;
    }

    /**
     * E-mail the results of the scheduled operation.
     *
     * @param  array $addresses
     * @return $this
     *
     * @throws \LogicException
     */
    public function emailOutputTo($addresses)
    {
        if (is_null($this->_output) || $this->_output == $this->getDefaultOutput()) {
            throw new InvalidCallException("Must direct output to a file in order to e-mail results.");
        }
        $addresses = is_array($addresses) ? $addresses : func_get_args();
        return $this->then(function (Application $app) use ($addresses) {
            $this->emailOutput($app->mailer, $addresses);
        });
    }

    /**
     * Register a callback to be called after the operation.
     *
     * @param  \Closure $callback
     * @return $this
     */
    public function then(\Closure $callback)
    {
        $this->_afterCallbacks[] = $callback;
        return $this;
    }

    /**
     * E-mail the output of the event to the recipients.
     *
     * @param MailerInterface $mailer
     * @param  array $addresses
     */
    protected function emailOutput(MailerInterface $mailer, $addresses)
    {
        $mailer->compose()
            ->setTextBody(file_get_contents($this->_output))
            ->setSubject($this->getEmailSubject())
            ->setTo($addresses)
            ->send();
    }

    /**
     * Get the e-mail subject line for output results.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        if ($this->_description) {
            return 'Scheduled Job Output (' . $this->_description . ')';
        }
        return 'Scheduled Job Output';
    }

    /**
     * Register a callback to the ping a given URL after the job runs.
     *
     * @param  string $url
     * @return $this
     */
    public function thenPing($url)
    {
        return $this->then(function () use ($url) {
            (new HttpClient)->get($url);
        });
    }

    /**
     * Set the human-friendly description of the event.
     *
     * @param  string $description
     * @return $this
     */
    public function description($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * Get the summary of the event for display.
     *
     * @return string
     */
    public function getSummaryForDisplay()
    {
        if (is_string($this->_description)) return $this->_description;
        return $this->buildCommand();
    }

    /**
     * Get the Cron expression for the event.
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->_expression;
    }

    public function getDefaultOutput()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return 'NUL';
        } else {
            return '/dev/null';
        }
    }
}
