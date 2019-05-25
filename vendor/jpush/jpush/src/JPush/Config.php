<?php
namespace JPush;

class Config {
    const DISABLE_SOUND = "_disable_Sound";
    const DISABLE_BADGE = 0x10000;
    const USER_AGENT = 'JPush-API-PHP-Client';
    const CONNECT_TIMEOUT = 20;
    const READ_TIMEOUT = 120;
    const DEFAULT_MAX_RETRY_TIMES = 3;
    const DEFAULT_LOG_FILE = "./jpush.log";
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_DELETE = 'DELETE';
    const HTTP_PUT = 'PUT';
}
