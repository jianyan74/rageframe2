## 模块Url生成辅助类

目录

- 直接生成Url
- 生成前台Url
- 生成微信Url
- 生成ApiUrl

引入

```
use common\helpers\AddonUrl;
```

### 直接生成Url

```
AddonUrl::to(['index']);
```

### 生成前台Url

```
AddonUrl::toFront(['index']);
```

### 生成微信Url

```
AddonUrl::toWechat(['index']);
```

### 生成ApiUrl

```
AddonUrl::toApi(['index']);
```