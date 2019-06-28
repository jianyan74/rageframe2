目录

- 测试域名
- 接口版本
- 北京时间格式
- 公共头部参数
- 公共出参说明
- 接口列表

#### 测试域名

```
http://www.example.com/api/addons/execute
```

#### 接口版本

v1

#### 北京时间格式

YYYYmmddHHiiss

#### 公共入参说明

> 注意是通过Url传递

入参说明

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
addon | string | 是 | rf-article | 模块名称 | 
route | string | 是 | 无 | 模块路由 | 
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

### 接口列表

- [文章管理](acticle.md)
- [文章分类](acticle-cate.md)
- [幻灯片](adv.md)