## 小程序登录注册

目录

- 加密数据进行解密认证


### 加密数据进行解密认证

请求地址(Post)

```
/v1/mini-program/login
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
signature | string| 是 | 无 | 使用 sha1( rawData + sessionkey ) 得到字符串，用于校验用户信息
encryptedData | string| 是 | 无 | 包括敏感数据在内的完整用户信息的加密数据
rawData | string| 是 | 无 | 不包括敏感信息的原始数据字符串，用于计算签名
iv | string| 是 | 无 | 加密算法的初始向量
code | string| 是 | 无 | 小程序code

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "refresh_token": "ZQqIzE91lZsOsiBZUzX_HRvH_er71IA3_1527339061",
        "access_token": "y7ch3kQtRq7dEkqf6le2LOyRNOB_xzQV_1527339061",
        "expiration_time": 172800,
        "member": {
            "id": 1,
            "username": "admin",
            "type": 1,
            "nickname": "简言",
            "realname": null,
            "head_portrait": null,
            "sex": 1,
            "qq": null,
            "email": "1@qq.com",
            "birthday": null,
            "user_money": "0.00",
            "accumulate_money": "0.00",
            "frozen_money": "0.00",
            "user_integral": 0,
            "address_id": "0",
            "visit_count": 9,
            "home_phone": null,
            "mobile": null,
            "role": 10,
            "last_time": 1527339061,
            "last_ip": "127.0.0.1",
            "provinces": 0,
            "city": 0,
            "area": 0,
            "allowance": 2,
            "allowance_updated_at": 1527339061,
            "status": 10,
            "append": 1511169880,
            "updated": 1527339061
        }
    }
}
```