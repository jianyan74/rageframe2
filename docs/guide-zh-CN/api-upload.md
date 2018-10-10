## 文件上传

目录

- 图片上传
- 视频上传
- 语音上传
- 文件上传
- base64上传
- 七牛上传
- OSS上传

### 图片上传

请求地址(Post)

```
/v1/file/images?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
file | string| 是 | 无 | 文件
thumb | array| 否 | 无 | 生成缩略图(具体看例子)

thumb 数组例子(生成`100*100`和`200*200`的缩略图)

```
{
	"thumb": [{
		"widget": 100,
		"height": 100
	}, {
		"widget": 200,
		"height": 200
	}]
}
```

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.jpg",
    }
}
```

### 视频上传

请求地址(Post)

```
/v1/file/videos?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
file | string| 是 | 无 | 文件

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.mp4",
    }
}
```

### 语音上传

请求地址(Post)

```
/v1/file/voices?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
file | string| 是 | 无 | 文件

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.jpg",
    }
}
```

### 文件上传

请求地址(Post)

```
/v1/file/files?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
file | string| 是 | 无 | 文件

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.jpg",
    }
}
```

### base64上传

请求地址(Post)

```
/v1/file/base64-img?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
image | string| 是 | 无 | 文件
extend | string| 否 | jpg | 文件后缀 

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.jpg",
    }
}
```

### 七牛上传

请求地址(Post)

```
/v1/file/qiniu?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
file | string| 是 | 无 | 文件

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.jpg",
    }
}
```

### OSS上传

请求地址(Post)

```
/v1/file/oss?access-token=[登陆获取到access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明
---|---|---|---|---
file | string| 是 | 无 | 文件

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "urlPath": "1.jpg",
    }
}
```