## 个人信息

目录

- 当前用户

### 当前用户

请求地址(Get)

```
/oauth2/v1/default
```

参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
authorization | string| 是 | 无 | Bearer + 空格 + access_token | 注意写入header头

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "id": 2,
        "access_token": "5aad206263fa4be72cb3241b28be3",
        "merchant_id": 1,
        "client_id": "1111111111",
        "member_id": "",
        "expires": "2019-06-06 12:07:21",
        "scope": [],
        "grant_type": "client_credentials",
        "status": 1,
        "created_at": 1559779635,
        "updated_at": 1559790441
    }
}
```