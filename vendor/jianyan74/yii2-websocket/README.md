## Yii2 WebSocket

即时通讯Demo

### 问题反馈

在使用中有任何问题，欢迎反馈给我，可以用以下联系方式跟我交流

QQ群：[655084090](https://jq.qq.com/?_wv=1027&k=4BeVA2r)

### 前提 

服务器安装swoole

```
 git clone https://github.com/swoole/swoole-src.git
 cd swoole-src
 phpize
 ./configure --enable-openssl -with-php-config=[PATH] #注意[PATH]为你的php地址 开启ssl用
 make && make install
 ```
### 安装
  
composer执行

```
composer require "jianyan74/yii2-websocket"
```

或者在 `composer.json` 加入

```
"jianyan74/yii2-websocket": "^1.0"
```
### 配置

 在 `common/config/main.php` 加入以下配置
  ```
     'redis' => [
         'class' => 'yii\redis\Connection',
         'hostname' => 'localhost',
         'port' => 6379,
         'database' => 0,
     ],
  ```
 在 `console/config/main.php` 加入以下配置。（注意：配置在controllerMap里面）
 
 ```
// webSocket
'websocket' => [
    'class' => 'jianyan\websocket\console\WebSocketController',
    'server' => 'jianyan\websocket\server\WebSocketServer', // 可替换为自己的业务类继承该类即可
    'host' => '0.0.0.0',// 监听地址
    'port' => 9501,// 监听端口
    'type' => 'ws', // 默认为ws连接，可修改为wss
    'config' => [// 标准的swoole配置项都可以再此加入
        'daemonize' => false,// 守护进程执行
        'task_worker_num' => 4,//task进程的数量
        'ssl_cert_file' => '',
        'ssl_key_file' => '',
        'pid_file' => __DIR__ . '/../../backend/runtime/logs/server.pid',
        'log_file' => __DIR__ . '/../../backend/runtime/logs/swoole.log',
        'log_level' => 0,
    ],
],
 ```
 
 ### 使用
 
  ```
  # 启动 
  php ./yii websocket/start
  # 停止 
  php ./yii websocket/stop
  # 重启 
  php ./yii websocket/restart
   ```
   
### 测试

```
<script>
    var wsl = 'ws://[to/your/url]:9501'; // 如果是wss的改成wss://[to/your/url]:9501
    ws = new WebSocket(wsl);// 新建立一个连接
    // 如下指定事件处理
    ws.onopen = function () {
        var login_data = '{"type":"login","nickname":"隔壁老王","head_portrait":"123","room_id":10001}';
        ws.send(login_data);
    };
    // 接收消息
    ws.onmessage = function (evt) {
        console.log(evt.data);
        /*ws.close();*/
    };
    // 关闭
    ws.onclose = function (evt) {
        console.log('WebSocketClosed!');
    };
    // 报错
    ws.onerror = function (evt) {
        console.log('WebSocketError!');
    };
    
    // 聊天
    function say(msg){
        if(msg){
            var data = '{"type":"say","to_client_id":"all","content":'+ msg +'}';
            ws.send(data);
        }
    }
</script>
```

### 接口文档

> 发送消息的格式全部以json字符串发过来

### 心跳

请求地址

```
ws://[to/you/url]:9501
```

参数

参数名 | 说明
---|---
type | pong

无返回

### 进入房间

请求地址

```
ws://[to/you/url]:9501
```

参数

参数名 | 说明
---|---
type | login
room_id | 房间id
user_id | 用户id
nickname | 用户昵称
head_portrait | 用户头像

返回(1)

```
{
　　"type":"login",
　　"from_client_id":2,
　　"to_client_id":"all",
　　"time":"2018-04-10 16:41:29",
　　"count":"1",
　　"member":{
　　　　"fd":2,
　　　　"room_id":10001,
　　　　"user_id":1,
　　　　"nickname":"隔壁老王",
　　　　"head_portrait":"123"
　　}
}
```

返回(2)
> 当前登录的人还会返回一个在线列表

```
{
　　"type":"list",
　　"from_client_id":2,
　　"to_client_id":2,
　　"time":"2018-04-10 16:41:29",
　　"list":[{
     　　"fd":2,
     　　"room_id":10001,
     　　"user_id":1,
     　　"nickname":"隔壁老王",
     　　"head_portrait":"123"
     }]
}
```

### 发言

请求地址

```
ws://[to/you/url]:9501
```

参数

参数名 | 说明
---|---
type | say
to_client_id | 对谁说话:默认 all
content | 内容

返回

```
{
　　"type":"say",
　　"from_client_id":2,
　　"to_client_id":"all",
　　"time":"2018-04-10 16:43:00",
　　"content":"123"
}
```

### 送礼物

请求地址

```
ws://[to/you/url]:9501
```

参数

参数名 | 说明
---|---
type | gift
gift_id | 礼物id

返回

```
{
	"type": "gift",
	"from_client_id": 4,
	"to_client_id": "all",
	"gift_id": "礼物id",
	"time": "2018-03-06 11:27:15"
}
```

### 离开房间

请求地址

```
ws://[to/you/url]:9501
```

参数

参数名 | 说明
---|---
type | leave

返回

```
{
	"type": "leave",
	"from_client_id": 1,
	"to_client_id": "all",
	"count": 2,
	"time": "2018-03-06 11:27:15"
}
```