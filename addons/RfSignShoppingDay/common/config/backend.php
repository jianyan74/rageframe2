<?php

return [
    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        'award/index' => '奖品管理',
        'award/edit' => '奖品编辑',
        'award/ajax-update' => '奖品状态修改',
        'award/delete' => '奖品删除',
        'record/index' => '中奖记录',
        'record/export' => '中奖导出',
        'user/index' => '用户管理',
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '奖品管理',
            'route' => 'award/index',
            'icon' => ''
        ],
        [
            'title' => '中奖记录',
            'route' => 'record/index',
            'icon' => ''
        ],
        [
            'title' => '用户管理',
            'route' => 'user/index',
            'icon' => ''
        ],
    ],
];