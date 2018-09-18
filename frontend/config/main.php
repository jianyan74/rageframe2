<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\member\MemberInfo',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
            'idParam' => '__frontend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /** ------ 路由配置 ------ **/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,  //这个是生成路由 ?r=site/about--->/site/about
            'showScriptName' => false,
            'suffix' => '.html',//静态
            'rules' =>[

            ],
        ],
    ],
    'controllerMap' => [
        // 文件上传公共控制器
        'file' => [
            'class' => 'common\controllers\FileBaseController',
        ],
        'ueditor' => [
            'class' => 'common\widgets\ueditor\UeditorController',
        ],
        // 插件渲染默认控制器
        'addons' => [
            'class' => 'common\controllers\AddonsController',
        ],
    ],
    'params' => $params,
];
