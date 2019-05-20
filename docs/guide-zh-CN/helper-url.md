## Url生成辅助类

目录

- 生成前台Url
- 生成微信Url
- 生成ApiUrl

引入

```
use common\helpers\Url;
```

### 生成前台Url

```
UrlHelper::toFront(['index']);
```

### 生成微信Url

```
UrlHelper::toWechat(['index']);
```

### 生成ApiUrl

```
UrlHelper::toApi(['index']);
```