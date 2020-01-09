<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    // 'catchAll' => ['site/offline'], // 全拦截路由(比如维护时可用)
    'modules' => [
        /** ------ 会员模块 ------ **/
        'member' => [
            'class' => 'frontend\modules\member\Module',
        ],
        /** ------ 开放平台模块 ------ **/
        'open' => [
            'class' => 'frontend\modules\open\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\member\Member',
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
        /** ------ i18n 国际化 ------ **/
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        /** ------ 第三方登录 ------ **/
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'qq' => [
                    'class' => 'xj\oauth\QqAuth',
                    'clientId' => '111',
                    'clientSecret' => '111',

                ],
                'weibo' => [
                    'class' => 'xj\oauth\WeiboAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'weixin' => [
                    'class' => 'xj\oauth\WeixinAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'wechat' => [
                    'class' => 'xj\oauth\WeixinMpAuth', // weixin mp
                    'clientId' => '111',
                    'clientSecret' => '',
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ]
        ]
    ],
    'controllerMap' => [
        // 文件上传公共控制器
        'file' => [
            'class' => 'common\controllers\FileBaseController',
        ],
        // 百度编辑器控制器
        'ueditor' => [
            'class' => 'common\widgets\ueditor\UeditorController',
        ]
    ],
    'params' => $params,
];
