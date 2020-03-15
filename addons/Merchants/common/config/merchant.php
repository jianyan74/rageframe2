<?php

return [

    // ----------------------- 默认配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-user-plus',
        ],
        // 子模块配置
        'modules' => [
            /** ------ 公用模块 ------ **/
            'common' => [
                'class' => 'addons\Merchants\merchant\modules\common\Module',
            ],
            /** ------ 基础模块 ------ **/
            'base' => [
                'class' => 'addons\Merchants\merchant\modules\base\Module',
            ],
        ],
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '网站设置',
            'route' => 'common/config/edit-all',
            'icon' => 'fa fa-cog',
            'params' => [],
        ],
        [
            'title' => '用户权限',
            'route' => 'baseAuth',
            'icon' => 'fa fa-user-secret',
            'params' => [],
            'child' => [
                [
                    'title' => '用户管理',
                    'route' => 'base/member/index',
                    'icon' => 'fa fa-user-plus',
                    'params' => []
                ],
                [
                    'title' => '角色管理',
                    'route' => 'base/auth-role/index',
                    'icon' => 'fa fa-user-plus',
                    'params' => []
                ],
            ]
        ],
        [
            'title' => '商户信息',
            'route' => 'merchant/edit',
            'icon' => 'fa fa-user-plus',
            'params' => []
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '网站设置',
            'name' => 'common/config/*',
        ],
        [
            'title' => '用户权限',
            'name' => 'baseAuth',
            'child' => [
                [
                    'title' => '用户管理',
                    'name' => 'base/member/*',
                ],
                [
                    'title' => '角色管理',
                    'name' => 'base/auth-role/*',
                ],
            ]
        ],
        [
            'title' => '商户信息',
            'name' => 'merchant/edit',
        ]
    ],
];