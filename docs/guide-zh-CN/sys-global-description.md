## 全局说明

目录

- 通用
  - 生成二维码
- 控制台
  - 数据迁移
  - 定时任务
- 控制器
  - 上传
  - 省市区联动
  - 接收微信消息
- 别名
- 变量
  - api
  - 后台
  - 微信
  - 前台

### 通用

##### 生成二维码

```
$qr = Yii::$app->get('qr');
Yii::$app->response->format = Response::FORMAT_RAW;
Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

return $qr->setText('www.rageframe.com')
    ->setLabel('2amigos consulting group llc')
    ->setSize(150)
    ->setMargin(7)
    ->writeString();
```
or

```
use Da\QrCode\QrCode;

$qrCode = (new QrCode('This is my text'))
    ->setSize(250)
    ->setMargin(5)
    ->useForegroundColor(51, 153, 255);

// 有多种使用方式

// 把图片保存到文件中:
$qrCode->writeFile(__DIR__ . '/code.png'); // 没有指定的时候默认为png格式

// 直接显示在浏览器 
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
```

### 控制台

##### 数据迁移

备份表

```
php ./yii migrate/backup all #备份全部表
php ./yii migrate/backup table1,table2,table3... #备份多张表
php ./yii migrate/backup table1 #备份一张表
```

恢复全部表

```
php ./yii migrate/up
```

##### 定时任务

> 注意需要在Linux环境下运行，且让PHP的system函数取消禁用  
> 表达式帮助：[cron表达式生成器](http://cron.qqe2.com/)

1、需要先设置cron ，让 ./yii cron/run 可以每分钟运行。

例如:

```
//每分钟执行一次定时任务
* * * * * /[你的项目地址]/yii cron/run
```

2、在 console/config/params.php 中加入新的定时任务：

```
    'cronJobs' => [
        // 清理过期的微信历史消息记录
        // 每天凌晨执行一次
        'msg-history/index' => [
            'cron' => '0 0 * * *',
            'cron-stdout'=> '/tmp/rageframe/cron/MsgHistory.log',// 成功日志
            'cron-stderr'=> '/tmp/rageframe/cron/MsgHistoryError.log',// 错误日志
        ]
        //......更多的定时任务
    ],
```

3、如果想修改定时任务运行时间可以在 `console/config/params.php `文件配置 `cron`

```
'cron' => '*/2 * * * *',
```

4、具体例子

查看控制器 `console\controllers\MsgHistoryController`

### 控制器

##### 上传

> api上传 有专门的说明，目前说的例子适用于 wechat、frontend、backend 的应用

##### 省市区联动

> 目前说的例子适用于 wechat、frontend、backend 的应用

##### 接收微信消息

> 目前说的例子适用于 wechat 的应用

### 别名

### 变量