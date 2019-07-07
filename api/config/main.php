<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [ // 版本1
            'class' => 'api\modules\v1\Module',
        ],
        'v2' => [ // 版本2
            'class' => 'api\modules\v2\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeSend' => 'api\behaviors\BeforeSend',
        ],
        'user' => [
            'identityClass' => 'common\models\api\AccessToken',
            'enableAutoLogin' => true,
            'enableSession' => false,// 显示一个HTTP 403 错误而不是跳转到登录界面
            'loginUrl' => null,
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
            'errorAction' => 'message/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // 美化Url,默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            'enablePrettyUrl' => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。启用容易出错
            // 注意:如果不需要严格解析路由请直接删除或注释此行代码
            'enableStrictParsing' => true,
            // 是否在URL中显示入口脚本。是对美化功能的进一步补充。
            'showScriptName' => false,
            // 指定续接在URL后面的一个后缀，如 .html 之类的。仅在 enablePrettyUrl 启用时有效。
            'suffix' => '',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        /**
                         * 默认登录测试控制器(Post)
                         * http://当前域名/api/v1/site/login
                         */
                        // 'sign-secret-key',
                        // 版本1
                        'v1/default',// 默认测试入口
                        'v1/site',
                        'v1/mini-program',
                        'v1/pay',
                        'v1/member/member',
                        'v1/member/address',
                        'v1/member/auth',
                        // 版本2
                        'v2/default', // 默认测试入口
                    ],
                    'pluralize' => false, // 是否启用复数形式，注意index的复数indices，开启后不直观
                    'extraPatterns' => [
                        'POST login' => 'login', // 登录获取token
                        'POST refresh' => 'refresh', // 重置token
                        'POST sms-code' => 'sms-code', // 获取验证码
                        'POST register' => 'register', // 注册
                        'POST up-pwd' => 'up-pwd', // 重置密码
                        // 测试查询可删除 例如：http://www.rageframe.com/api/v1/default/search
                        'GET search' => 'search',
                        'GET session-key' => 'session-key', // 小程序获取session key
                        'POST decode' => 'decode', // 解密获取小程序用户信息数据
                        'GET qr-code' => 'qr-code', // 获取小程序码
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/file'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST images' => 'images', // 图片上传
                        'POST videos' => 'videos', // 视频上传
                        'POST voices' => 'voices', // 语音上传
                        'POST files' => 'files', // 文件上传
                        'POST base64' => 'base64', // base64上传 其他上传权限自己添加
                        'POST merge' => 'merge', // 合并分片
                    ],
                ],
                [
                    'class' => 'api\rest\UrlRule',
                    'controller' => ['addons'],
                    'pluralize' => false,
                ],
            ]
        ],
    ],
    'params' => $params,
];
