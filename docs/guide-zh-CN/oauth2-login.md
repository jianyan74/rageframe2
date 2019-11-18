## 授权/刷新Token

目录

- authorization_code(授权码模式)
- implicit(简化模式)
- password(密码模式)
- client_credentials(客户端模式)
- refresh_token(刷新access_token)

### authorization_code(授权码模式)

*第一步*

> 先登录获取code

请求地址(Get)

```
/open/authorize/index
```

Params 参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
response_type | string| 是 | 无 | 固定填写 code |
client_id | string| 是 | 无 | 客户端标识 | 
redirect_uri | string| 否 | 无 | 回调跳转Url | 如果不填写者默认调用系统内填写的授权Url
state | string| 否 | 无 | CSRF令牌 | CSRF令牌的值存储在用户会话中，以便在他们返回时进行验证

返回

```

```

*第二步*

> 获取授权令牌

请求地址(Post)

```
/oauth2/authorize
```

Body 参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
grant_type | string| 是 | 无 | 固定填写 authorization_code |
client_id | string| 是 | 无 | 客户端标识 | 
client_secret | string| 是 | 无 | 客户端秘钥 | 
redirect_uri | string| 是 | 无 | 授权Url |
code | string| 是 | 无 | 授权回调code | 

返回

```

```

### implicit(简化模式)

请求地址(Get)

```
/open/implicit
```

Params 参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
response_type | string| 是 | 无 | 固定填写 token |
client_id | string| 是 | 无 | 客户端标识 | 
redirect_uri | string| 否 | 无 | 回调跳转Url | 如果不填写者默认调用系统内填写的授权Url
scope | string| 否 | 无 | 授权获取信息类型 |
state | string| 否 | 无 | CSRF令牌 | CSRF令牌的值存储在用户会话中，以便在他们返回时进行验证

返回

```

```

### password(密码模式)

请求地址(Post)

```
/oauth2/password
```

Body 参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
grant_type | string| 是 | 无 | 固定填写 password |
client_id | string| 是 | 无 | 客户端标识 | 
client_secret | string| 是 | 无 | 客户端秘钥 | 
username | string| 是 | 无 | 账号 | 
password | string| 是 | 无 | 密码 | 
scope | string| 否 | 无 | 授权获取信息类型 |

返回

```

```

### client_credentials(客户端模式)

请求地址(Post)

```
/oauth2/client-credentials
```

Body 参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
grant_type | string| 是 | 无 | 固定填写 client_credentials |
client_id | string| 是 | 无 | 客户端标识 | 
client_secret | string| 是 | 无 | 客户端秘钥 | 
scope | string| 否 | 无 | 授权获取信息类型 |

返回

```

```

### refresh_token(刷新access_token)

请求地址(Post)

```
/oauth2/refresh-token
```

Body 参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
grant_type | string| 是 | 无 | 固定填写 refresh_token |
refresh_token | string| 是 | 无 | 重置令牌 | 
client_id | string| 是 | 无 | 客户端标识 | 
client_secret | string| 是 | 无 | 客户端秘钥 | 
scope | string| 否 | 无 | 授权获取信息类型 |

返回

```

```