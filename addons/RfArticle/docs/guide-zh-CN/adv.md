## 幻灯片

请求地址(Get)

```
/api/addons/execute?route=adv/index
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
            "title": "幻灯片2",
            "cover": "http://www.example.com/attachment/images/2019-01/03/image_154648861757561025.jpg",
            "location_id": "1",
            "silder_text": "",
            "start_time": "1546488600",
            "end_time": "1548390900",
            "jump_link": "",
            "jump_type": "1",
            "sort": "0",
            "status": "1",
            "created_at": "1546488622",
            "updated_at": "1546493670"
        },
        {
            "id": "2",
            "title": "幻灯片1",
            "cover": "http://www.example.com/attachment/images/2019-01/03/image_154649197897514854.jpg",
            "location_id": "1",
            "silder_text": "测试",
            "start_time": "1546491960",
            "end_time": "1691812500",
            "jump_link": "",
            "jump_type": "1",
            "sort": "0",
            "status": "1",
            "created_at": "1546491990",
            "updated_at": "1546493680"
        }
    ]
}
```