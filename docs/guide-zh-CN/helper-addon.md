### 模块辅助类

目录

- 获取资源文件路径
- 获取/设置当前模块配置信息

#### 获取资源文件路径

> 注意 : 方法只能在视图里面使用，如果改动了资源，请清理对应应用的 assets 目录，因为初始化创建后不会再重新创建资源

```
use common\helpers\AddonHelper;
```

```
// 获取静态资源文件所在目录 该方法获取的内容为 addons\[模块]\resources\
$path = AddonHelper::filePath();

// 获取静态资源文件 该方法获取的内容为 addons\[模块]\resources\img\test.jpg 文件
AddonHelper::file('img/test.jpg');
```

#### 获取/设置当前模块配置信息

```
// 获取
AddonHelper::getConfig();

// 设置
AddonHelper::setConfig($config)
```