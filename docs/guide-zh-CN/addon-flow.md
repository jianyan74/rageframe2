## 模块开发流程

目录

- 创建/维护
- 权限
- 数据迁移
- 继承的基类说明
- 开发

> 注意：修改完毕配置后需要手动取插件列表更新配置或者卸载重新安装，不然配置是不会生效的

### 创建/维护

进入后台 - 系统管理 - 应用管理 - 设计新插件

> 创建成功后会在 根目录的 addons 目录下生成插件文件

### 权限

权限请在创建的模块下的 common/config 的各自应用文件内手动填写，安装后会自动注册进系统权限管理

例如：

```
    // ----------------------- 默认配置 ----------------------- //

    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-puzzle-piece',
        ],
        // 子模块配置，代表注册插件的子模块进系统，方便模块化开发
        'modules' => [
            'v1' => [
                'class' => 'addons\TinyShop\api\modules\v1\Module',
            ],
            'v2' => [
                'class' => 'addons\TinyShop\api\modules\v2\Module',
            ],
        ],
    ],

    /**
     * 可授权权限
     *
     * @var array
     */
    'authItem' => [
        [
            'title' => '一级权限',
            'name' => 'test',
            'child' =>[
                'title' => '二级权限',
                'name' => 'test/*', // 支持通配符 *， 插件下所有以 test/ 为前缀的路由都可以通过
            ]
        ],
    ];
    
    // ----------------------- 快捷入口 ----------------------- //
    
    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '一级菜单',
            'route' => 'curd/index',
            'icon' => '',
            'params' => [
                'test' => '1'
            ],
            'child' => [
                [
                    'title' => '二级菜单',
                    'route' => 'test/index',
                ],
            ]
        ]
    ],
```

查看设置权限：系统管理->用户权限->角色管理->创建/编辑

### 数据迁移

可以用系统自带的开发工具插件生成对应的数据迁移文件，并遵循系统规范进行数据安装

### 继承的基类说明

##### 后台

默认继承插件各自内自动生成的 BaseController，如果自己有特殊需求可以修改继承

##### api

> 注意：开发 api 的时候能使用 restful 的基类，但是不受路由规则管辖

- 控制器请全部继承 `api\controllers\OnAuthController`,注意Curd是改过的，不想用系统的Curd可直接继承 `api\controllers\ActiveController`，如果设置控制器内方法不需要验证请设置 `authOptional` 属性
- 用户私有控制器请全部继承 `api\controllers\UserAuthController`

##### merapi

> 注意：开发 merapi 的时候能使用 restful 的基类，但是不受路由规则管辖

- 控制器请全部继承 `merapi\controllers\OnAuthController`,注意Curd是改过的，不想用系统的Curd可直接继承 `merapi\controllers\ActiveController`，如果设置控制器内方法不需要验证请设置 `authOptional` 属性
- 用户私有控制器请全部继承 `merapi\controllers\UserAuthController`


### 开发

完全可以根据Yii2正常的开发流程去开发对应的控制器、视图、插件内的应用
