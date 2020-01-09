<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=rageframe',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'rf_',
            'attributes' => [
                // PDO::ATTR_STRINGIFY_FETCHES => false, // 提取的时候将数值转换为字符串
                // PDO::ATTR_EMULATE_PREPARES => false, // 启用或禁用预处理语句的模拟
            ],
            // 'enableSchemaCache' => true, // 是否开启缓存, 请了解其中机制在开启，不了解谨慎
            // 'schemaCacheDuration' => 3600, // 缓存时间
            // 'schemaCache' => 'cache', // 缓存名称
        ],
        /**
        // redis缓存
        // 注意：系统默认开启了file缓存的保存路径，如果开启redis或者其他缓存请去main里面删除
         * 'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        // session写入缓存配置
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ],
        ],
         */
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
