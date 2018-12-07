<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'sourceLanguage' => 'zh-cn',
    'timeZone' => 'Asia/Shanghai',
    'bootstrap' => [
        'queue' // 队列系统
    ],
    'components' => [
        /** ------ 格式化时间 ------ **/
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        /** ------ 缓存 ------ **/
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@backend/runtime/cache' // 注意如果要改成非文件缓存请删除，否则会报错
        ],
        /** ------ 网站碎片管理 ------ **/
        'debris' => [
            'class' => 'common\components\Debris',
        ],
        /** ------ redis配置 ------ **/
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        /** ------ 队列设置 ------ **/
        'queue' => [
             'class' => 'yii\queue\redis\Queue',
             'redis' => 'redis', // 连接组件或它的配置
             'channel' => 'queue', // Queue channel key
            'as log' => 'yii\queue\LogBehavior',// 日志
        ],
        /** ------ 全文搜索引擎 ------ **/
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        /** ------ xunsearch搜索引擎 ------ **/
        'xunsearch' => [
            'class' => 'hightman\xunsearch\Connection', // 此行必须
            'iniDirectory' => '@common/config',    // 搜索 ini 文件目录，默认：@vendor/hightman/xunsearch/app
            'charset' => 'utf-8',   // 指定项目使用的默认编码，默认即时 utf-8，可不指定
        ],
        /** ------ 微信SDK ------ **/
        'wechat' => [
            'class' => 'jianyan\easywechat\Wechat',
            'userOptions' => [],  // 用户身份类参数
            'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
            'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
        ],
        /** ------ 公用支付 ------ **/
        'pay' => [
            'class' => 'common\components\Pay',
        ],
        /** ------ 二维码 ------ **/
        'qr' => [
            'class' => '\Da\QrCode\Component\QrCodeComponent',
            // ... 您可以在这里配置组件的更多属性
        ],
        /** ------ 服务 ------ **/
        'services' => [
            'class' => 'common\services\Application',
        ]
    ],
];
