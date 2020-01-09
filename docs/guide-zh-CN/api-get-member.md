## 个人信息

目录

- 详情
- 修改

> 注意这里的ID为用户ID

### 详情

请求地址(Get)

```
/v1/member/member/[ID]?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "id": "1",
        "username": "admin",
        "nickname": "简言",
        "realname": null,
        "head_portrait": null,
        "sex": "1",
        "qq": null,
        "email": "1@qq.com",
        "birthday": null,
        "user_money": "0.00",
        "user_integral": "0",
        "status": "1",
        "created_at": "1511169880"
    }
}
```

### 修改

请求地址(Put)

```
/v1/member/member/[ID]?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "id": "1",
        "username": "admin",
        "nickname": "简言",
        "realname": null,
        "head_portrait": null,
        "sex": "1",
        "qq": null,
        "email": "1@qq.com",
        "birthday": null,
        "user_money": "0.00",
        "user_integral": "0",
        "status": "1",
        "created_at": "1511169880"
    }
}
```