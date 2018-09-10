## 模块Url生成辅助类

```
//引入类
use common\helpers\AddonUrl;
```

直接生成Url

```
echo AddonUrl::to(['index']);
```

生成前台Url

```
echo AddonUrl::toFront(['index']);
```

生成微信

```
echo AddonUrl::toWechat(['index']);
```

生成Api

```
echo AddonUrl::toApi(['index']);
```