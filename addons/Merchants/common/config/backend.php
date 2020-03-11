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
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        'merchant/*' => '商户管理',
        'cate/*' => '商户分类',
        'menu/*' => '菜单管理',
        'menu-cate/*' => '菜单分类',
        'config/*' => '配置管理',
        'config-cate/*' => '配置分类',
        'auth-item/*' => '权限管理',
        'auth-role/*' => '角色管理',
        'member/*' => '用户管理',
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '商户统计',
            'route' => 'stat/index',
            'icon' => 'fa fa-line-chart',
            'params' => []
        ],
        [
            'title' => '商户信息',
            'route' => 'merchant/index',
            'icon' => 'fa fa-user-plus',
            'params' => []
        ],
        [
            'title' => '商户申请',
            'route' => 'merchant/apply',
            'icon' => 'fa fa-file-text-o',
            'params' => []
        ],
        [
            'title' => '商户功能',
            'route' => 'merchantFunction',
            'icon' => 'fa fa-list',
            'params' => [],
            'child' => [
                [
                    'title' => '商户分类',
                    'route' => 'cate/index',
                ],
                [
                    'title' => '商户菜单',
                    'route' => 'menu/index',
                ],
                [
                    'title' => '商户配置',
                    'route' => 'config/index',
                ],
                [
                    'title' => '默认角色',
                    'route' => 'auth-role-default/edit',
                ],
                [
                    'title' => '商户权限',
                    'route' => 'auth-item/index',
                ],
            ],
        ]
    ],
];