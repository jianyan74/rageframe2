<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
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
                        'web-hook',// git自动更新钩子
                        // 'sign-secret-key',
                        // 版本1
                        'v1/default',// 默认测试入口
                        'v1/site',
                        'v1/mini-program',
                        'v1/mini-program-pay',
                        'v1/member/info',
                        'v1/member/address',
                        // 版本2
                        'v2/default',// 默认测试入口
                    ],
                    'pluralize' => false,// 是否启用复数形式，注意index的复数indices，开启后不直观
                    'extraPatterns' => [
                        'POST gitee' => 'gitee', // 码云钩子
                        'POST login' => 'login',// 登录获取token
                        'POST refresh' => 'refresh',// 重置token
                        // 测试查询可删除 http://当前域名/api/v1/member/member/search
                        'GET search' => 'search',
                        'GET session-key' => 'session-key',// 小程序获取session key
                        'POST decode' => 'decode',// 解密获取小程序用户信息数据
                        'POST find-token-by-openid' => 'find-token-by-openid',// 通过openid返回token
                    ],
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
                        'POST base64-img' => 'base64-img', // base64上传 其他上传权限自己添加
                        'POST qiniu' => 'qiniu', // 七牛上传
                        'POST oss' => 'oss', // 阿里云oss上传
                        'POST merge' => 'merge', // 合并分片
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['addons',],
                    'pluralize' => false,// 是否启用复数形式，注意index的复数indices，开启后不直观
                    'extraPatterns' => [
                        'GET execute' => 'execute', // 插件渲染
                        'POST execute' => 'execute', // 插件渲染
                        'PUT execute' => 'execute', // 插件渲染
                        'DELETE execute' => 'execute', // 插件渲染
                    ],
                ],
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeSend' => 'api\behaviors\beforeSend',
        ],
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'message/error',
        ],
    ],
    'controllerMap' => [
        // 插件渲染默认控制器
        'addons' => [
            'class' => 'common\controllers\AddonsController',
        ],
    ],
    'params' => $params,
];
