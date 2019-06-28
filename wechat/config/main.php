<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'wechat',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'wechat\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-wechat',
        ],
        'user' => [
            'identityClass' => 'common\models\member\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-wechat', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
            'idParam' => '__wechat',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the wechat
            'name' => 'advanced-wechat',
            'timeout' => 86400,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m/d') . '.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function($event) {
                Yii::$app->services->log->record($event->sender);
            },
        ],
    ],
    'controllerMap' => [
        // 微信数据处理
        'api' => [
            'class' => 'common\controllers\WechatApiController',
        ]
    ],
    'params' => $params,
];
