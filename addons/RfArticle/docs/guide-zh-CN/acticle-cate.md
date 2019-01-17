## 文章分类

请求地址(Get)

```
/api/addons/execute?route=article-cate/index
```

入参说明

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
addon | string | 是 | rf-article | 模块名称 | 

返回

```
{
    "code": 200,
    "message": "OK",
    "data": [
        {
            "id": "2",
            "title": "日常",
            "sort": "0",
            "level": "1",
            "pid": "0",
            "status": "1",
            "created_at": "1546493593",
            "updated_at": "1546493593"
        },
        {
            "id": "1",
            "title": "新闻",
            "sort": "0",
            "level": "1",
            "pid": "0",
            "status": "1",
            "created_at": "1546493578",
            "updated_at": "1546493578"
        }
    ]
}
```