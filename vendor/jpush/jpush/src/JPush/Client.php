<?php
namespace JPush;
use InvalidArgumentException;

class Client {

    private $appKey;
    private $masterSecret;
    private $retryTimes;
    private $logFile;
    private $zone;
    private static $zones = [
        'DEFAULT' => [
            'push' => 'https://api.jpush.cn/v3/',
            'report' => 'https://report.jpush.cn/v3/',
            'device' => 'https://device.jpush.cn/v3/devices/',
            'alias' => 'https://device.jpush.cn/v3/aliases/',
            'tag' => 'https://device.jpush.cn/v3/tags/',
            'schedule' => 'https://api.jpush.cn/v3/schedules/'
        ],
        'BJ' => [
            'push'      => 'https://bjapi.push.jiguang.cn/v3/',
            'report'    => 'https://bjapi.push.jiguang.cn/v3/report/',
            'device'    => 'https://bjapi.push.jiguang.cn/v3/device/',
            'alias'     => 'https://bjapi.push.jiguang.cn/v3/device/aliases/',
            'tag'       => 'https://bjapi.push.jiguang.cn/v3/device/tags/',
            'schedules' => 'https://bjapi.push.jiguang.cn/v3/push/schedules/'
        ]
    ];

    public function __construct($appKey, $masterSecret, $logFile=Config::DEFAULT_LOG_FILE, $retryTimes=Config::DEFAULT_MAX_RETRY_TIMES, $zone = null) {
        if (!is_string($appKey) || !is_string($masterSecret)) {
            throw new InvalidArgumentException("Invalid appKey or masterSecret");
        }
        $this->appKey = $appKey;
        $this->masterSecret = $masterSecret;
        if (!is_null($retryTimes)) {
            $this->retryTimes = $retryTimes;
        } else {
            $this->retryTimes = 1;
        }
        $this->logFile = $logFile;
        if (!is_null($zone) && in_array(strtoupper($zone), array_keys(self::$zones))) {
            $this->zone = strtoupper($zone);
        } else {
            $this->zone= null;
        }
    }

    public function push() { return new PushPayload($this); }
    public function report() { return new ReportPayload($this); }
    public function device() { return new DevicePayload($this); }
    public function schedule() { return new SchedulePayload($this);}

    public function getAuthStr() { return $this->appKey . ":" . $this->masterSecret; }
    public function getRetryTimes() { return $this->retryTimes; }
    public function getLogFile() { return $this->logFile; }

    public function is_group() {
        $str = substr($this->appKey, 0, 6);
        return $str === 'group-';
    }

    public function makeURL($key) {
        if (is_null($this->zone)) {
            return self::$zones['DEFAULT'][$key];
        } else {
            return self::$zones[$this->zone][$key];
        }
    }
}
