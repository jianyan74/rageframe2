## 小程序登录注册

目录

- 验证Code
- 加密数据进行解密认证
- 通过openid获取最新token


### 验证Code

请求地址(Get)

```
/v1/mini-program/session-key
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
code | string| 是 | 无 | 小程序code

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "auth_key": "UMJSTQ_BG6D7xyM5E4ws2AcZ_A2DIsZj_1534928459" // 临时授权秘钥
    }
}
```

### 加密数据进行解密认证

请求地址(Post)

```
/v1/mini-program/decode
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
signature | string| 是 | 无 | 使用 sha1( rawData + sessionkey ) 得到字符串，用于校验用户信息
encryptedData | string| 是 | 无 | 包括敏感数据在内的完整用户信息的加密数据
rawData | string| 是 | 无 | 不包括敏感信息的原始数据字符串，用于计算签名
iv | string| 是 | 无 | 加密算法的初始向量
auth_key | string| 是 | 无 | 授权秘钥

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
     
     }
}
```