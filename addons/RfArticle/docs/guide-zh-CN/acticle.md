## 文章管理

请求地址(Get)

```
/api/addons/execute?route=article/index
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
            "id": "1",
            "title": "这是一篇测试的文章",
            "cover": "http://www.example.com/attachment/images/2019-01/03/image_154649362899485153.jpg",
            "description": "简介",
            "view": "0"
        }
    ]
}
```

## 文章详情

请求地址(Get)

```
/api/addons/execute?route=article/view
```

入参说明

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
addon | string | 是 | rf-article | 模块名称 | 
id | int | 是 |  | 文章id | 

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "id": 1,
        "title": "这是一篇测试的文章",
        "cover": "http://www.example.com/attachment/images/2019-01/03/image_154649362899485153.jpg",
        "seo_key": "",
        "seo_content": "",
        "cate_id": null,
        "description": "简介",
        "position": 3,
        "content": "<p>内容<br/></p>",
        "link": "",
        "author": "简言",
        "view": 0,
        "sort": 0,
        "status": 1,
        "created_at": 1546493646,
        "updated_at": 1546493646
    }
}
```