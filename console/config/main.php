<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        /** ------ 数据库命令行备份 ------ **/
        'migrate' => [
            'class' => 'jianyan\migration\ConsoleController',
        ],
        /** ------ websocket ------ **/
        'websocket' => [
            'class' => 'common\controllers\WebSocketController',
            'server' => 'common\components\WebSocketServer',
            'host' => '0.0.0.0',// 监听地址
            'port' => 9501, // 监听端口
            'type' => 'ws', // 默认为ws连接，可修改为wss
            'config' => [ // 标准的swoole配置项都可以再此加入
                'daemonize' => false, // 守护进程执行
                'task_worker_num' => 4, //task进程的数量
                // 'ssl_cert_file' => '',
                // 'ssl_key_file' => '',
                'pid_file' => __DIR__ . '/../../backend/runtime/logs/server.pid',
                'log_file' => __DIR__ . '/../../backend/runtime/logs/swoole.log',
                'log_level' => 0,
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
