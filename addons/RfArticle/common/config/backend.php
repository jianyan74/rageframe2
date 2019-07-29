<?php

return [
    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        'article-single/index' => '单页管理',
        'article-single/edit' => '单页编辑',
        'article-single/ajax-update' => '单页状态修改',
        'article-single/delete' => '单页删除',
        'article/index' => '文章首页',
        'article/edit' => '文章编辑',
        'article/ajax-update' => '文章状态修改',
        'article/hide' => '文章删除',
        'article-cate/index' => '文章分类首页',
        'article-cate/ajax-edit' => '文章分类编辑',
        'article-cate/ajax-update' => '文章分类状态修改',
        'article-cate/delete' => '文章分类删除',
        'article-tag/index' => '文章标签首页',
        'article-tag/ajax-edit' => '文章标签编辑',
        'article-tag/ajax-update' => '文章标签状态修改',
        'article-tag/delete' => '文章标签删除',
        'adv/index' => '幻灯片首页',
        'adv/edit' => '幻灯片编辑',
        'adv/ajax-update' => '幻灯片状态修改',
        'adv/delete' => '幻灯片删除',
        'article/recycle' => '回收站',
        'article/show' => '回收站还原',
        'article/delete' => '回收站删除',
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '单页管理',
            'route' => 'article-single/index',
            'icon' => 'fa fa-pencil-square-o'
        ],
        [
            'title' => '文章管理',
            'route' => 'article/index',
            'icon' => 'fa fa-list'
        ],
        [
            'title' => '文章分类',
            'route' => 'article-cate/index',
            'icon' => ''
        ],
        [
            'title' => '文章标签',
            'route' => 'article-tag/index',
            'icon' => 'fa fa-tags'
        ],
        [
            'title' => '幻灯片',
            'route' => 'adv/index',
            'icon' => 'fa fa-file-image-o'
        ],
        [
            'title' => '回收站',
            'route' => 'article/recycle',
            'icon' => 'fa fa-trash-o'
        ],
    ],
];