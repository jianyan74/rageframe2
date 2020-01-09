<?php

return [
    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [

    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //
    'menuConfig' => [
         'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
         'icon' => 'fa fa-puzzle-piece',
    ],

    'menu' => [
        [
            'title' => '二维码生成',
            'route' => 'qr/index',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '数据备份',
            'route' => 'data-base/backups',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '队列监控',
            'route' => 'queue/info',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '系统探针',
            'route' => 'system/probe',
            'icon' => '',
            'params' => [
            ],
        ],
    ],
];