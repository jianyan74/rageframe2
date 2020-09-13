<?php
return [
    'name' => 'RageFrame',
    'version' => '2.6.57',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'sourceLanguage' => 'zh-cn',
    'timeZone' => 'Asia/Shanghai',
    'bootstrap' => [
        'queue', // 队列系统
        'common\components\Init', // 加载默认的配置
    ],
    'components' => [
        /** ------ 缓存 ------ **/
        'cache' => [
            'class' => 'yii\caching\FileCache',
            /**
             * 文件缓存一定要有，不然有可能会导致缓存数据获取失败的情况
             *
             * 注意如果要改成非文件缓存请删除，否则会报错
             */
            'cachePath' => '@backend/runtime/cache'
        ],
        /** ------ 格式化时间 ------ **/
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        /** ------ 服务层 ------ **/
        'services' => [
            'class' => 'services\Application',
        ],
        /** ------ redis配置 ------ **/
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        /** ------ websocket redis配置 ------ **/
        'websocketRedis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 1,
        ],
        /** ------ 网站碎片管理 ------ **/
        'debris' => [
            'class' => 'common\components\Debris',
        ],
        /** ------ 访问设备信息 ------ **/
        'mobileDetect' => [
            'class' => 'Detection\MobileDetect',
        ],
        /** ------ 队列设置 ------ **/
        'queue' => [
            'class' => 'yii\queue\redis\Queue',
            'redis' => 'redis', // 连接组件或它的配置
            'channel' => 'queue', // Queue channel key
            'as log' => 'yii\queue\LogBehavior',// 日志
        ],
        /** ------ 公用支付 ------ **/
        'pay' => [
            'class' => 'common\components\Pay',
        ],
        /** ------ 上传组件 ------ **/
        'uploadDrive' => [
            'class' => 'common\components\UploadDrive',
        ],
        /** ------ 快递查询 ------ **/
        'logistics' => [
            'class' => 'common\components\Logistics',
        ],
        /** ------ 二维码 ------ **/
        'qr' => [
            'class' => '\Da\QrCode\Component\QrCodeComponent',
            // ... 您可以在这里配置组件的更多属性
        ],
        /** ------ 微信SDK ------ **/
        'wechat' => [
            'class' => 'common\components\Wechat',
            'userOptions' => [],  // 用户身份类参数
            'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
            'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
            'rebinds' => [
                'cache' => 'common\components\WechatCache',
            ]
        ],
    ],
];
