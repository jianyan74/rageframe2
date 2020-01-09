## 线上性能优化

目录

- 性能优化
 
 ### 性能优化
 
##### 1、开启生产环境，在项目初始化的时候执行以下代码，并配置项目信息
 
 ```
 php init // 然后输入1回车,再输入yes回车
 ```
 
##### 2、开启 OPC 缓存  
##### 3、开启 AR 数据库的 schema 缓存
 
 > 注意：如果修改数据结构，在更新完SQL语句之后需要先关闭Schema再开启，数据结构的修改才会生效，或者直接清空缓存
 
```
return [
    // ...
    'components' => [
        // ...
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=rageframe',
            'username' => 'root',
            'password' => '',
            'enableSchemaCache' => true, // 是否开启缓存
            'schemaCacheDuration' => 3600, // 缓存时间
            'schemaCache' => 'cache', // 缓存名称
        ],
    ],
];
```
##### 4、使用别的存储方式session
  
例如：redis、memcache、mysql
  
##### 5、多使用局部缓存，整页缓存 ，http 缓存等，复杂数据库查询也可以做缓存依赖
 
##### 6、数据库索引等优化，尽量多的使用视图（当有必要）

##### 7、查询操作limit限制

查询结果使用AsArray，这样可以节省内存因为这样返回的是数组，而不是对象，譬如：
```
$posts = Post::find()->orderBy('id desc')->limit(100)->asArray()->all();
```
##### 8、最小化的使用assets

这个玩意，是需要加载生成js和css的

##### 9、Composer Atuoloader 优化

命令：
```
php composer.phar dumpautoload -o
```
##### 10、通过脚本处理中间数据
  
可以通过cron定时任务批量处理数据，譬如产品的特价，产品的过滤等等
