## WebSocket

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
        var login_data = '{"route":"site.test","content":"123"}';
        ws.send(login_data);
    };
    // 接收消息
    ws.onmessage = function (evt) {
        console.log(evt.data);
        /*ws.close();*/
        say("哈哈哈");
    };
    // 关闭
    ws.onclose = function (evt) {
        console.log('WebSocketClosed!');
    };
    // 报错
    ws.onerror = function (evt) {
        console.log('WebSocketError!');
    };

    var num = 1;
    // 聊天
    function say(msg){
        if(msg){
            var data = '{"route":"site.test","content":"'+msg + num +'"}';
            ws.send(data);
            num++;
        }
    }
</script>
```