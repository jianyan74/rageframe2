group (组别) | 说明
---|---
pc | PC
ios | 苹果
android | 安卓
h5 | H5
wechat | 微信小程序
ali | 支付宝小程序
qq | QQ小程序
dingTalk | 钉钉小程序
touTiao | 头条小程序

> 注意：如果是商户 api 的需要安装商户插件，并创建用户信息进行登录

## 登录重置

目录

- 登录
- 重置令牌

### 登录

请求地址(Post)

```
/v1/site/login
```

参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
username | string| 是 | 无 | 账号 |
password | string| 是 | 无 | 密码 | 
`group` | string| 是 | 无 | 组别 | 

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "refresh_token": "1V1XLG8DQkROK6g-Rh5k17hZuZHQVZB9_1527339048",
        "access_token": "neRlaCJcbMQHgPmZqRjqYgVBfFZUe7lm_1527339048",
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
            "visit_count": 8,
            "home_phone": null,
            "mobile": null,
            "role": 10,
            "last_time": 1527339048,
            "last_ip": "127.0.0.1",
            "provinces": 0,
            "city": 0,
            "area": 0,
            "allowance": 2,
            "allowance_updated_at": 1527339048,
            "status": 10,
            "append": 1511169880,
            "updated": 1527339048
        }
    }
}
```

### 重置令牌

请求地址(Post)

```
/v1/site/refresh
```

参数

参数名 | 参数类型 | 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
refresh_token | string| 是 | 无 | 重置令牌 |
group | string| 是 | 无 | 组别 | 

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