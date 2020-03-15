## 控制台

目录

- 自带默认任务
- 数据迁移
- 定时任务

### 自带默认任务

```
// 定时拉取钉钉提醒
yii pull-remind/ding-talk

// 定时拉取系统提醒
yii pull-remind/sys

// 定时清理微信历史消息(需安装微信插件)
yii wechat/msg-history/index

// 定时群发微信消息(需安装微信插件)
yii wechat/send-message/index
```

### 数据迁移

备份表

```
 # 备份全部表
php ./yii migrate/backup all
 
php ./yii migrate/backup table1,table2,table3... # 备份多张表
php ./yii migrate/backup table1 #备份一张表
```

恢复全部表

```
php ./yii migrate/up
```

### 定时任务

> 注意需要在Linux环境下运行，且让PHP的system函数取消禁用  
> 表达式帮助：[cron表达式生成器](http://cron.qqe2.com/)

表达式:

```
Linux
*    *    *    *    *    *
-    -    -    -    -    -
|    |    |    |    |    |
|    |    |    |    |    + year [optional]
|    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
|    |    |    +---------- month (1 - 12)
|    |    +--------------- day of month (1 - 31)
|    +-------------------- hour (0 - 23)
+------------------------- min (0 - 59)
```

1、需要先设置cron ，让 ./yii schedule/run --scheduleFile=@console/config/schedule.php 可以每分钟运行。

例如:

```
// 每分钟执行一次定时任务
* * * * * /path-to-your-project/yii schedule/run --scheduleFile=@console/config/schedule.php 1>> /tmp/rageframeConsoleLog.text 2>&1
```

2、在 console/config/schedule.php 中加入新的定时任务：

```
/**
 * 清理过期的微信历史消息记录
 *
 * 每天凌晨执行一次
 */
$schedule->command('test/index')->cron('0 0 * * *');

/**
 * 定时群发微信消息
 *
 * 每分钟执行一次
 */
$schedule->command('test/index')->cron('* * * * *');
```

4、具体例子

查看控制器 `console\controllers\MsgHistoryController`

更多使用文档：https://github.com/omnilight/yii2-scheduling