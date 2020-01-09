目录

- MongoDB

### MongoDB

安装

这个扩展需要MongoDB PHP扩展版本1.0.0或更高。  
这个扩展需要MongoDB服务器版本3.0或更高版本。  

执行

```
composer require --prefer-dist yiisoft/yii2-mongodb
```

或 composer.json 里面添加

```
"yiisoft/yii2-mongodb": "~2.1.0"
```

要使用这个扩展,只需在应用程序中添加以下代码  
配置:

```
return [
    //....
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://@localhost:27017/mydatabase',
            'options' => [
                "username" => "Username",
                "password" => "Password"
            ]
        ],
    ],
];
```

增加说明

```
    'mongodb' => [
        'class' => 'yii\mongodb\Connection',
        // 有账户的配置
        //'dsn' => 'mongodb://username:password@localhost:27017/datebase',
        // 无账户的配置
        'dsn' => 'mongodb://127.0.0.1:27017/fecshop',
        // 复制集
        //'dsn' => 'mongodb://10.10.10.252:10001/erp,mongodb://10.10.10.252:10002/erp,mongodb://10.10.10.252:10004/erp?replicaSet=terry&readPreference=primaryPreferred',
    ],
```

debug配置

```
$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'panels' => [
        'mongoDB' => \yii\mongodb\debug\MongoDbPanel::class,
    ],
];
```