<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../../oauth2/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../../common/config/test.php',
    require __DIR__ . '/../../common/config/test-local.php',
    require __DIR__ . '/../../oauth2/config/main.php',
    require __DIR__ . '/../../oauth2/config/main-local.php',
    require __DIR__ . '/../../oauth2/config/test.php',
    require __DIR__ . '/../../oauth2/config/test-local.php'
);

/**
 * 打印
 *
 * @param $array
 */
function p($array){
    echo "<pre>";
    print_r($array);
}

(new yii\web\Application($config))->run();
