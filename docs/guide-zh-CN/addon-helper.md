## 模块辅助说明

目录

- 获取模块信息
- 获取模块路由和基础信息
- 获取模块导航和菜单信息
- 生成模块Url地址
- 辅助方法

#### 获取模块信息

```
Yii::$app->params['addon'];
```

#### 获取模块路由和基础信息

```
Yii::$app->params['addonInfo'];
```

示例

```
Array
(
    [name] => RfExample
    [oldRoute] => curd/index
    [moduleId] => backend
    [oldController] => curd
    [oldAction] => index
    [controller] => Curd
    [action] => Index
    [controllerName] => CurdController
    [actionName] => actionIndex
    [rootPath] => \addons\RfExample
    [rootAbsolutePath] => E:\local\rageframe-advanced/addons/RfExample
    [controllersPath] => \addons\RfExample\backend\controllers\CurdController
)
```

#### 获取模块导航和菜单信息

```
Yii::$app->params['addonBinding'];
```

#### 生成模块Url地址

[模块Url生成辅助类](helper-addon-url.md)

####  辅助方法

[模块辅助类](helper-addon.md)