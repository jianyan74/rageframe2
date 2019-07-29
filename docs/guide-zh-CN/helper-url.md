## Url生成辅助类

目录

- 生成前台 Url
- 生成微信 Url
- 生成Api Url
- 生成OAuth2 Url
- 生成Storage Url

引入

```
use common\helpers\Url;
```

### 生成前台 Url

```
UrlHelper::toFront(['index']);
```

### 生成微信 Url

```
UrlHelper::toWechat(['index']);
```

### 生成Api Url

```
UrlHelper::toApi(['index']);
```

### 生成OAuth2 Url

```
UrlHelper::toOAuth2(['index']);
```

### 生成Storage Url

```
UrlHelper::toStorage(['index']);
```