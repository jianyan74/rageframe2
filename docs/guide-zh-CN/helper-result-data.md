## 格式化数据返回辅助类

目录

- Json
- Api
- 返回示例

引入

```
use common\helpers\ResultHelper;
```

#### Json

> 如果是 api/oauth2 那边调用注意 要符合 http 状态码

```
/**
* 返回json数据格式
*
* @param int $code 状态码
* @param string $message 返回的报错信息
* @param array|object $data 返回的数据结构
*/
ResultHelper::json($code, $message, $data)
```

#### 返回示例

```
{
    "code": 200,
    "message": "OK",
    "data": [
        
    ]
}
```