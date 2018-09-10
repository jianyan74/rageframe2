## 变量

目录

- 获取模块信息
- 获取模块路由和基础信息
- 获取模块导航和菜单信息

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