## 格式化数据返回辅助类

目录

- Json
- Api
- 返回示例

引入

```
use common\helpers\ResultDataHelper;
```

#### Json

```
/**
* 返回json数据格式
*
* @param int $code 状态码
* @param string $message 返回的报错信息
* @param array|object $data 返回的数据结构
*/
ResultDataHelper::json($code, $message, $data)
```

#### Api

```
/**
 * 返回 array 数据格式 api 自动转为 json
 *
 * @param int $code 状态码 注意：要符合http状态码
 * @param string $message 返回的报错信息
 * @param array|object $data 返回的数据结构
 */
ResultDataHelper::api($code, $message, $data)
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