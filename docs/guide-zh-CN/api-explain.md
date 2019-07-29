## 接口说明

目录

- 测试域名
- 接口版本
- 北京时间格式
- 公共头部参数
- 公共出参说明
- 公用请求方法
- 公共状态码说明

#### 测试域名

```
http://www.example.com/api/接口版本/
```

#### 接口版本

v1

#### 北京时间格式

YYYYmmddHHiiss

#### 公共入参说明

> 注意是通过Url传递
> 例如 `http://www.example.com/api/v1/member/info?access-token=[access-token]`

入参说明

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
access-token | string | 否 | 无 | 授权秘钥 | 需登录验证(出现401错误)必传

#### 公共出参说明

出参说明

参数名 | 参数类型 | 说明 | 备注
---|---|---|---
code | int | 状态码 | 
message | string | 状态说明 | 
data | array | 接口数据 |

成功返回

```
{
    "code": 200,
    "message": "ok",
    "data": [
    
    ]
}
``` 

错误返回

```
{
    "code": 422,
    "message": "错误说明",
    "data": [
    
    ]
}
```

header出参说明

参数名 | 参数类型 | 说明 | 备注
---|---|---|---
X-Rate-Limit-Limit | int | 同一个时间段所允许的请求的最大数目 | 
X-Rate-Limit-Remaining | int | 在当前时间段内剩余的请求的数量 |
X-Rate-Limit-Reset | int | 为了得到最大请求数所等待的秒数 |
X-Pagination-Total-Count | int | 总数量 | 
X-Pagination-Page-Count | int | 总页数 | 
X-Pagination-Current-Page | int | 当前页数 |
X-Pagination-Per-Page | int | 每页数量 |

> 注意：如果自行修改了系统默认的首页查询，需要自行设置header头

#### 公用请求方法

针对不同操作，服务器向用户返回的结果应该符合以下规范。

方法 | 说明 | 对应控制器方法(路由)
---|---|---
GET /article | 获取文章列表 | /article/index
GET /article/1 | 获取文章详情(id为1) | /article/view?id=1
POST /article | 创建一篇文章 | /article/create
PUT /article/1 | 更新文章(id为1) | /article/update?id=1
DELETE /article/1 | 删除文章(id为1) | /article/delete?id=1

> 如果想自定义控制器内的方法(不包含：index/view/create/update/delete)，需要自行在`rule`里面配置`extraPatterns`

#### 公共状态码说明

* `200`: OK。一切正常。
* `201`: 响应 `POST` 请求时成功创建一个资源。`Location` header
   包含的URL指向新创建的资源。
* `204`: 该请求被成功处理，响应不包含正文内容 (类似 `DELETE` 请求)。
* `304`: 资源没有被修改。可以使用缓存的版本。
* `400`: 错误的请求。可能通过用户方面的多种原因引起的，例如在请求体内有无效的JSON
   数据，无效的操作参数，等等。
* `401`: 验证失败。
* `403`: 已经经过身份验证的用户不允许访问指定的 API 末端。
* `404`: 所请求的资源不存在。
* `405`: 不被允许的方法。 请检查 `Allow` header 允许的HTTP方法。
* `415`: 不支持的媒体类型。 所请求的内容类型或版本号是无效的。
* `422`: 数据验证失败 (例如，响应一个 `POST` 请求)。 请检查响应体内详细的错误消息。
* `429`: 请求过多。 由于限速请求被拒绝。
* `500`: 内部服务器错误。 这可能是由于内部程序错误引起的。
