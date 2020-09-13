## Url生成辅助类

目录

- 生成前台 Url
- 生成微信 Url
- 生成Api Url
- 生成OAuth2 Url
- 生成Storage Url
- 生成不自带 merchant_id 的 Url

引入

```
use common\helpers\Url;
```

### 生成前台 Url

```
Url::toFront(['index']);
```

### 生成微信 Url

```
Url::toHtml5(['index']);
```

### 生成Api Url

```
Url::toApi(['index']);
```

### 生成OAuth2 Url

```
Url::toOAuth2(['index']);
```

### 生成Storage Url

```
Url::toStorage(['index']);
```

### 生成不自带 merchant_id 的 Url

> 下面例子生成为不带 merchant_id 的前台地址

```
Url::removeMerchantIdUrl('toFront', ['index']);
```
