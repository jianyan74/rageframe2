## 模块介绍及目录

目录

- 模块介绍
- 启动流程
- 商户映射
- 目录结构

### 模块介绍

> 定位: 实现独立的功能模块或者临时性使用的功能模块或者就是工具类型的功能，例如：小游戏(大转盘/消消乐/抽奖/大屏互动/红包等),小插件(广告管理/文章管理/友情链接等等),小模块(报名/投票/签到),小程序,大型插件微商城等等  

### 启动流程

Yii 入口文件->启动文件(common\components\Init.php)->初始化已安装的插件->写入模块组->根据 Yii 正常的开发和访问流程去开发访问插件

### 商户映射

开启了开启了商户映射，访问后台的路由会直接转到商户端去访问商户端的路由，由商户端的控制器方法来处理该路由。此功能方便有些插件商户端和后台端功能一致，又不想重复开发进行的一个便利功能

注意：

开启商户映射后，需要开启插件的启动项，并加入以下代码

```
Yii::$app->services->merchant->addId(0);
```

### 目录结构

目录 | 说明
---|---
**Demo** | 模块名称
--- **backend** | 模块后台应用
--- --- resources | 资源(js/css/image)目录(不可修改命名)
--- --- controllers | 控制器目录
--- --- --- SettingController.php | 配置文件控制器, 有配置项的话可选
--- --- views | 视图目录
--- --- asset | 资源配置目录
--- --- ---  Asset.php | 后台静态资源载入器
--- --- --- setting | 配置视图目录
--- --- --- --- hook.php | 钩子视图文件
--- --- --- --- display.php | 配置视图文件
--- **frontend** | 模块前台应用
--- --- resources | 资源(js/css/image)目录(不可修改命名)
--- --- controllers | 控制器目录
--- --- views | 视图目录
--- --- asset | 资源配置目录
--- --- ---  Asset.php | 前台静态资源载入器
--- **html5** | 模块html5应用
--- --- resources | 资源(js/css/image)目录(不可修改命名)
--- --- controllers | 控制器目录
--- --- views | 视图目录
--- --- asset | 资源配置目录
--- --- ---  Asset.php | html5静态资源载入器
--- **merchant** | 模块html5应用
--- --- resources | 资源(js/css/image)目录(不可修改命名)
--- --- controllers | 控制器目录
--- --- views | 视图目录
--- --- asset | 资源配置目录
--- --- ---  Asset.php | merchant静态资源载入器
--- **api** | 模块api应用(主要用于小程序)
--- --- controllers | 控制器目录
--- **common** | 公用
--- --- models | 公共模型层
--- --- components | 组件
--- --- --- Bootstrap | 引导文件，插件启动前会访问该文件
--- --- config | 配置：例如权限、菜单、导航入口
--- **console** | 控制层
--- --- migrations | 数据迁移文件
--- AddonConfig.php | 模块配置文件(必须有)
--- AddonMessage.php | 模块微信消息接收处理文件(可选)
--- Install | 安装SQL文件(文件名可自定义详细看DebrisAddon.php)
--- UnInstall | 卸载SQL文件(同上)
--- Upgrade | 更新SQL文件(同上)