<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-wechat',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'wechat\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-wechat',
        ],
        'user' => [
            'identityClass' => 'common\models\member\MemberInfo',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-wechat', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
            'idParam' => '__wechat',
        ],
        'session' => [
            // 用于登录微信的会话cookie的名称
            'name' => 'advanced-wechat',
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
        /** ------ 路由配置 ------ **/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,  // 这个是生成路由 ?r=site/about--->/site/about
            'showScriptName' => false,
            'suffix' => '.html',// 静态
            'rules' =>[

            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function($event) {
                Yii::$app->services->errorLog->record($event->sender);
            },
        ],
    ],
    'controllerMap' => [
        // 微信数据处理
        'api' => [
            'class' => 'common\controllers\WechatApiController',
        ],
        // 插件渲染默认控制器
        'addons' => [
            'class' => 'common\controllers\AddonsController',
        ],
    ],
    'params' => $params,
];
