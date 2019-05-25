<?php
namespace Codeception\PHPUnit\ResultPrinter;

use Codeception\Event\FailEvent;
use Codeception\Events;
use Codeception\Test\Unit;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class UI extends \PHPUnit\TextUI\ResultPrinter
{
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher, $options, $out = null)
    {
        parent::__construct($out, $options['verbosity'] > OutputInterface::VERBOSITY_NORMAL, $options['colors'] ? 'always' : 'never');
        $this->dispatcher = $dispatcher;
    }

    protected function printDefect(\PHPUnit\Framework\TestFailure $defect, $count)
    {
        $this->write("\n---------\n");
        $this->dispatcher->dispatch(
            Events::TEST_FAIL_PRINT,
            new FailEvent($defect->failedTest(), null, $defect->thrownException(), $count)
        );
    }

    /**
     * @param \PHPUnit\Framework\TestFailure $defect
     */
    protected function printDefectTrace(\PHPUnit\Framework\TestFailure $defect)
    {
        $this->write($defect->getExceptionAsString());
        $this->writeNewLine();

        $stackTrace = \PHPUnit\Util\Filter::getFilteredStacktrace($defect->thrownException(), false);

        foreach ($stackTrace as $i => $frame) {
            if (!isset($frame['file'])) {
                continue;
            }

            $this->write(
                sprintf(
                    "#%d %s(%s)",
                    $i + 1,
                    $frame['file'],
                    isset($frame['line']) ? $frame['line'] : '?'
                )
            );

            $this->writeNewLine();
        }
    }

    public function startTest(\PHPUnit\Framework\Test $test)
    {
        if ($test instanceof Unit) {
            parent::startTest($test);
        }
    }

    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
        if ($test instanceof \PHPUnit\Framework\TestCase or $test instanceof \Codeception\Test\Test) {
            $this->numAssertions += $test->getNumAssertions();
        }

        $this->lastTestFailed = false;
    }

    public function addError(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }

    public function addSkippedTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->lastTestFailed = true;
    }
}
