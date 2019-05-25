<?php

error_reporting(E_ALL | E_STRICT);

// include the composer autoloader
$autoloader = require __DIR__ . '/../vendor/autoload.php';

// autoload abstract TestCase classes in test directory
$autoloader->add('Omnipay', __DIR__);

define('UNIONPAY_ASSET_DIR', realpath(__DIR__ . '/Assets'));

$configFile = realpath(__DIR__ . '/config.php');

if (file_exists($configFile) && false) {
    include_once $configFile;
} else {
    include_once realpath(__DIR__ . '/config.dist.php');
}

if (! function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $arg) {
            var_dump($arg);
        }
        exit(0);
    }
}
