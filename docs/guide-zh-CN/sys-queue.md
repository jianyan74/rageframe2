## 消息队列

目录 

- DEMO 类
- 处理队列
- 作业状态
- 控制台
- 进程监视器 Supervisor
- 定时任务监控

> 注意需要在Linux环境下运行
  该扩展为官方扩展，系统默认的队列驱动为redis，请自行安装redis，更多驱动请查看yii2-queue官方说明
  
### DEMO 类

每个被发送到队列的任务应该被定义为一个单独的类。
例如，如果您需要下载并保存一个文件，该类可能看起来如下:

```
class DownloadJob extends BaseObject implements \yii\queue\JobInterface
{
    public $url;
    public $file;
    
    public function execute($queue)
    {
        file_put_contents($this->file, file_get_contents($this->url));
    }
}
```

下面是将任务添加到队列:

```
Yii::$app->queue->push(new DownloadJob([
    'url' => 'http://example.com/image.jpg',
    'file' => '/tmp/image.jpg',
]));
```

将作业推送到队列中延时5分钟运行:

```
Yii::$app->queue->delay(5 * 60)->push(new DownloadJob([
    'url' => 'http://example.com/image.jpg',
    'file' => '/tmp/image.jpg',
]));
```

> 注意: 只有一部分驱动支持延时运行。

### 处理队列

> 具体执行任务的方式取决于所使用的驱动程序。大多数驱动都可以使用控制台命令，组件在您的应用程序中注册。有关更多细节，请参见相关驱动文档。
  
### 作业状态

该组件具有跟踪被推入队列的作业状态的能力。

```
// 将作业推送到队列并获得其ID
$id = Yii::$app->queue->push(new SomeJob());

// 这个作业等待执行。
Yii::$app->queue->isWaiting($id);

// Worker 从队列获取作业，并执行它。
Yii::$app->queue->isReserved($id);

// Worker 作业执行完成。
Yii::$app->queue->isDone($id);
```

> 注意: RabbitMQ 驱动不支持作业状态。

### Debug

```
$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'panels' => [
        'queue' => \yii\queue\debug\Panel::class,
    ],
];
```

### 控制台

控制台用于监听和处理队列任务。

```
yii queue/listen [wait]
```

listen 命令启动一个守护进程，它可以无限查询队列。如果有新的任务，他们立即得到并执行。
wait 是下一次查询队列的时间 当命令正确地通过 supervisor 来实现时，这种方法是最有效的。

```
yii queue/run
```

run 命令获取并执行循环中的任务，直到队列为空。适用与cron。

run 与 listen 命令的参数:

- --verbose, -v: 将执行状态输出到控制台。

- --isolate: 详细模式执行作业。如果启用，将打印每个作业的执行结果。

- --color: 高亮显示输出结果。
    
```
yii queue/info
```

### 进程监视器 Supervisor

Supervisor 是Linux的进程监视器。
它会自动启动您的控制台进程。
安装在Ubuntu上，你需要运行命令:

```
sudo apt-get install supervisor
```

Supervisor 配置文件通常可用 `/etc/supervisor/conf.d`。
你可以创建任意数量的配置文件。

配置示例:

```
[program:yii-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/my_project/yii queue/listen --verbose=1 --color=0
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/my_project/log/yii-queue-worker.log
```

在这种情况下，Supervisor 会启动4个 queue/listen worker。输出将写入相应日志文件。

有关 Supervisor 配置和使用的更多信息，请参阅文档。

以守护进程模式启动的Worker使用 queue/listen 命令支持 [File]、 [Db]、 [Redis]、 [RabbitMQ]、 [Beanstalk]、 [Gearman] 驱动。 有关其他参数，请参阅驱动程序指南。

### 定时任务监控 Cron

可以用 cron 开始 worker。需要使用 queue/run 命令。只要队列包含作业，它就能进行执行。

配置示例:

```
* * * * * /path-to-your-project/yii queue/run
```

在这种情况下，cron将每分钟启动一次命令。

queue/run 命令支持 [File]、[Db]、[Redis]、[Beanstalk]、[Gearman]驱动。有关其他选项，请参阅驱动程序指南。