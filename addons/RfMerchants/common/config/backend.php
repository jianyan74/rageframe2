<?php

return [
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
        'manager/index' => '用户管理',
        'manager/edit' => '用户编辑',
        'manager/ajax-edit' => '用户账号密码',
        'manager/ajax-update' => '用户状态修改',
        'manager/destroy' => '用户删除',
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '商户管理',
            'route' => 'merchant/index',
            'icon' => '',
            'params' => []
        ],
    ],
];