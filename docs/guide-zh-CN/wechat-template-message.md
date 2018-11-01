## 模版消息

目录

- 发送模板消息
- 发送一次性订阅消息

### 发送模板消息

```
$app->template_message->send([
        'touser' => 'user-openid',
        'template_id' => 'template-id',
        'url' => 'https://easywechat.org',
        'data' => [
            'key1' => 'VALUE',
            'key2' => 'VALUE2',
            ...
        ],
    ]);
```

### 发送一次性订阅消息

```
$app->template_message->send([
        'touser' => 'user-openid',
        'template_id' => 'template-id',
        'url' => 'https://easywechat.org',
        'data' => [
            'key1' => 'VALUE',
            'key2' => 'VALUE2',
            ...
        ],
    ]);
 ```
 
如果你想为发送的内容字段指定颜色，你可以将 "data" 部分写成下面 4 种不同的样式，不写 
 
 ```  
'data' => [
    'foo' => '你好',  // 不需要指定颜色
    'bar' => ['你好', '#F00'], // 指定为红色
    'baz' => ['value' => '你好', 'color' => '#550038'], // 与第二种一样
    'zoo' => ['value' => '你好'], // 与第一种一样
]
```