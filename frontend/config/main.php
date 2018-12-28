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
    'language' => 'zh-CN', // 语言包
    'controllerNamespace' => 'frontend\controllers',
    // 'catchAll' => ['site/offline'], // 全拦截路由(比如维护时可用)
    'modules' => [
        /** ------ 会员模块 ------ **/
        'member' => [
            'class' => 'frontend\modules\member\Module',
        ],
    ],
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
            // 用于登录前台的会话cookie的名称
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
        /** ------ 路由配置 ------ **/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,  // 这个是生成路由 ?r=site/about--->/site/about
            'showScriptName' => false,
            'suffix' => '.html',// 静态
            'rules' =>[

            ],
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
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function($event){
                Yii::$app->services->errorLog->record($event->sender);
            },
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
                'renren' => [
                    'class' => 'xj\oauth\RenrenAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                 'wechat' => [
                     'class' => 'xj\oauth\WeixinMpAuth', // weixin mp
                     'clientId' => '111',
                     'clientSecret' => '',
                 ],
                'amazon' => [
                    'class' => 'xj\oauth\AmazonAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    // 'clientId' => '1',
                    // 'clientSecret' => '1',
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
        ],
        // 插件渲染默认控制器
        'addons' => [
            'class' => 'common\controllers\AddonsController',
        ],
    ],
    'params' => $params,
];
