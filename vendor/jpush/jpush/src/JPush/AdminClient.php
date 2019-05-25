<?php
namespace JPush;

class AdminClient {

    const ADMIN_URL = 'https://admin.jpush.cn/v1/app/';

    private $devKey;
    private $devSecret;
    private $retryTimes;
    private $logFile;

    function __construct($devKey, $devSecret) {
        if (!is_string($devKey) || !is_string($devSecret)) {
            throw new InvalidArgumentException("Invalid devKey or devSecret");
        }
        $this->devKey = $devKey;
        $this->devSecret = $devSecret;
        $this->retryTimes = 1;
        $this->logFile = null;
    }

    public function getAuthStr() { return $this->devKey . ":" . $this->devSecret; }
    public function getRetryTimes() { return $this->retryTimes; }
    public function getLogFile() { return $this->logFile; }

    public function createApp($appName, $androidPackage, $groupName=null) {
        $url = AdminClient::ADMIN_URL;
        $body = [
            'app_name' => $appName,
            'android_package'=> $androidPackage,
            'group_name' => $groupName

        ];
        return Http::post($this, $url, $body);
    }

    public function deleteApp($appKey) {
        $url = AdminClient::ADMIN_URL . $appKey . '/delete';
        return Http::post($this, $url, []);
    }
}
