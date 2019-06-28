## 模块介绍及目录

目录

- 模块介绍
- 目录结构

### 模块介绍

> 定位: 实现小一点的功能模块，或者临时性使用的功能模块，或者就是小工具类型的功能，例如：小游戏(大转盘/消消乐/抽奖/大屏互动/红包等),小插件(广告管理/文章管理/友情链接等等),小模块(小型商城/报名/投票/签到),小程序等等  

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
--- **wechat** | 模块微信应用
--- --- resources | 资源(js/css/image)目录(不可修改命名)
--- --- controllers | 控制器目录
--- --- views | 视图目录
--- --- asset | 资源配置目录
--- --- ---  Asset.php | 微信静态资源载入器
--- **api** | 模块api应用(主要用于小程序)
--- --- controllers | 控制器目录
--- **common** | 公用
--- --- models | 公共模型层
--- AddonConfig.php | 模块配置文件(必须有)
--- AddonMessage.php | 模块微信消息接收处理文件(可选)
--- Install | 安装SQL文件(文件名可自定义详细看DebrisAddon.php)
--- UnInstall | 卸载SQL文件(同上)
--- Upgrade | 更新SQL文件(同上)