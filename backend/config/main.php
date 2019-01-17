<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'main', // 默认控制器
    'bootstrap' => ['log'],
    'modules' => [
        /** ------ 系统模块 ------ **/
        'sys' => [
            'class' => 'backend\modules\sys\Module',
        ],
        /** ------ 微信模块 ------ **/
        'wechat' => [
            'class' => 'backend\modules\wechat\Module',
        ],
        /** ------ 会员模块 ------ **/
        'member' => [
            'class' => 'backend\modules\member\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\sys\Manager',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
            'idParam' => '__backend',
            'as afterLogin' => 'backend\behaviors\AfterLogin',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'timeout' => 7200
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    // 日志存储到数据库
                    // 'class' => 'yii\log\DbTarget',
                    // 'logTable' => '{{%sys_log}}',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m/d') . '.log',
                ],
            ],
        ],
        /** ------ 错误定向页 ------ **/
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
        /** ------ RBAC配置 ------ **/
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%sys_auth_item}}',
            'assignmentTable' => '{{%sys_auth_assignment}}',
            'itemChildTable' => '{{%sys_auth_item_child}}',
            'ruleTable' => '{{%sys_auth_rule}}',
        ],
        /** ------ 资源替换 ------ **/
        'assetManager' => [
            // 线上建议将forceCopy设置成false，如果访问量不大无所谓
            'forceCopy' => true,
            //'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => []
                ],
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
        // 文件上传公共控制器
        'file' => [
            'class' => 'common\controllers\FileBaseController',
        ],
        'ueditor' => [
            'class' => 'common\widgets\ueditor\UeditorController',
        ],
        'provinces' => [
            'class' => 'backend\widgets\provinces\ProvincesController',
        ],
        'wechat-select-attachment' => [
            'class' => 'backend\widgets\wechatselectattachment\WechatSelectAttachment',
        ],
    ],
    'params' => $params,
];
