### 模块辅助类

目录

- 获取资源文件路径

#### 获取资源文件路径

> 注意 : 方法只能在视图里面使用

```
use common\helpers\AddonHelper;
```

```
// 获取静态资源文件所在目录 该方法获取的内容为 addons\[模块]\resources\ 目录
AddonHelper::getResourcesUrl();

// 获取静态资源文件 该方法获取的内容为 addons\[模块]\resources\img\test.jpg 文件
AddonHelper::getResourcesFile('img/test.jpg');
```