## 文件上传

目录

- 图片上传
- 视频上传
- 语音上传
- 文件上传
- base64上传

### 图片上传

请求地址(Post)

```
/v1/file/images?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
file | string| 是 | 无 | 文件 | 
drive | string| 否 | local | 本地上传 | oss:阿里云;qiniu:七牛;cos:腾讯
thumb | array| 否 | 无 | 生成缩略图(具体看例子) | 

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
        "url": "1.jpg",
    }
}
```

### 视频上传

请求地址(Post)

```
/v1/file/videos?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
file | string| 是 | 无 | 文件 | 
drive | string| 否 | local | 本地上传 | oss:阿里云;qiniu:七牛;cos:腾讯

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "url": "1.mp4",
    }
}
```

### 语音上传

请求地址(Post)

```
/v1/file/voices?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
file | string| 是 | 无 | 文件 | 
drive | string| 否 | local | 本地上传 | oss:阿里云;qiniu:七牛;cos:腾讯

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "url": "1.jpg",
    }
}
```

### 文件上传

请求地址(Post)

```
/v1/file/files?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
file | string| 是 | 无 | 文件 | 
drive | string| 否 | local | 本地上传 | oss:阿里云;qiniu:七牛;cos:腾讯

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "url": "1.jpg",
    }
}
```

### base64上传

请求地址(Post)

```
/v1/file/base64?access-token=[access-token]
```

参数

参数名 | 参数类型| 必填 | 默认 | 说明 | 备注
---|---|---|---|---|---
image | string| 是 | 无 | 文件 | 
drive | string| 否 | local | 本地上传 | oss:阿里云;qiniu:七牛;cos:腾讯
extend | string| 否 | jpg | 文件后缀 | 

返回

```
{
    "code": 200,
    "message": "OK",
    "data": {
        "url": "1.jpg",
    }
}
```