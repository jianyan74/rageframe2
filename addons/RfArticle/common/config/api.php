<?php

return [
    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [

    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [
        [
            'title' => '幻灯片管理',
            'route' => 'adv/index',
            'icon' => ''
        ],
        [
            'title' => '文章分类管理',
            'route' => 'article-cate/index',
            'icon' => ''
        ],
        [
            'title' => '文章管理',
            'route' => 'article/index',
            'icon' => ''
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //
    'menuConfig' => [
         'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
         'icon' => 'fa fa-puzzle-piece',
    ],

    'menu' => [

    ],
];