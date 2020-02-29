<?php

return [

    // ----------------------- 菜单配置 ----------------------- //
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
        'merchant/index' => '商户管理',
        'merchant/ajax-edit' => '商户编辑',
        'merchant/ajax-update' => '商户状态修改',
        'merchant/delete' => '商户删除',
        'role/index' => '角色管理',
        'role/edit' => '角色编辑',
        'role/ajax-update' => '角色状态修改',
        'role/delete' => '角色删除',
        'menu/index' => '菜单管理',
        'menu/ajax-edit' => '菜单编辑',
        'menu/ajax-update' => '菜单状态修改',
        'menu/delete' => '菜单删除',
        'menu-cate/index' => '菜单分类',
        'menu-cate/ajax-edit' => '菜单分类编辑',
        'menu-cate/ajax-update' => '菜单分类状态修改',
        'menu-cate/delete' => '菜单分类删除',
        'config/index' => '配置管理',
        'config/ajax-edit' => '配置编辑',
        'config/ajax-update' => '配置状态修改',
        'config/delete' => '配置删除',
        'config-cate/index' => '配置分类',
        'config-cate/ajax-edit' => '配置分类编辑',
        'config-cate/ajax-update' => '配置分类状态修改',
        'config-cate/delete' => '配置分类删除',
        'auth-item/index' => '权限管理',
        'auth-item/ajax-edit' => '权限编辑',
        'auth-item/ajax-update' => '权限状态修改',
        'auth-item/delete' => '权限删除',
        'member/index' => '用户管理',
        'member/edit' => '用户编辑',
        'member/ajax-edit' => '用户账号密码',
        'member/ajax-update' => '用户状态修改',
        'member/destroy' => '用户删除',
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '商户信息',
            'route' => 'merchant/index',
            'icon' => 'fa fa-user-plus',
            'params' => []
        ],
        [
            'title' => '商户菜单',
            'route' => 'menu/index',
            'icon' => 'fa fa-list',
            'params' => []
        ],
        [
            'title' => '商户配置',
            'route' => 'config/index',
            'icon' => 'fa fa-gear',
            'params' => []
        ],
        [
            'title' => '商户权限',
            'route' => 'auth-item/index',
            'icon' => 'fa fa-user-secret',
            'params' => []
        ],
    ],
];