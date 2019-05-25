<?php
use aliyun\core\regions\Endpoint;
use aliyun\core\regions\EndpointConfig;
use aliyun\core\regions\EndpointProvider;

// ensure we get report on all possible php errors
error_reporting(-1);
// require composer autoloader if available
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($composerAutoload)) {
    require_once($composerAutoload);
}
require_once(__DIR__ . '/TestCase.php');