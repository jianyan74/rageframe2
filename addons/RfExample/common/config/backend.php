<?php

return [
    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        'curd/index' => 'Curd首页',
        'curd/edit' => 'Curd编辑',
        'curd/ajax-update' => 'Curd状态修改',
        'curd/export' => 'Curd导出',
        'curd/delete' => 'Curd删除',
        'grid-curd/index' => 'Grid首页',
        'grid-curd/edit' => 'Grid编辑',
        'grid-curd/ajax-update' => 'Grid状态修改',
        'grid-curd/delete' => 'Grid删除',
        'modal/index' => 'Model首页',
        'modal/view' => 'Model详细',
        'mongo-db-curd/index' => 'MongoDb首页',
        'mongo-db-curd/edit' => 'MongoDb编辑',
        'mongo-db-curd/ajax-update' => 'MongoDb状态修改',
        'mongo-db-curd/delete' => 'MongoDb删除',
        'redis-curd/index' => 'Redis首页',
        'redis-curd/edit' => 'Redis编辑',
        'redis-curd/ajax-update' => 'Redis状态修改',
        'redis-curd/delete' => 'Redis删除',
        'elastic-search/index' => 'ES首页',
        'elastic-search/edit' => 'ES编辑',
        'elastic-search/ajax-update' => 'ES状态修改',
        'elastic-search/delete' => 'ES删除',
        'xunsearch/index' => 'Xunsearch首页',
        'xunsearch/edit' => 'Xunsearch编辑',
        'xunsearch/ajax-update' => 'Xunsearch状态修改',
        'xunsearch/delete' => 'Xunsearch删除',
        'queue/index' => '消息队列',
        'cate/index' => '分类首页',
        'cate/ajax-edit' => '分类编辑',
        'cate/ajax-update' => '分类状态修改',
        'cate/delete' => '分类删除',
        'video/cut-image' => '截取视频指定帧',
        'excel/index' => 'excel导入数据',
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => 'Curd',
            'route' => 'curd/index',
            'icon' => '',
            'params' => [
                'test' => '1'
            ]
        ],
        [
            'title' => 'Curd Grid',
            'route' => 'grid-curd/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => 'Modal案例',
            'route' => 'modal/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => 'MongoDb Curd',
            'route' => 'mongo-db-curd/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => 'Redis Curd',
            'route' => 'redis-curd/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => 'Elasticsearch',
            'route' => 'elastic-search/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => 'Xunsearch',
            'route' => 'xunsearch/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => '消息队列',
            'route' => 'queue/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => '无限级分类',
            'route' => 'cate/index',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => '截取视频指定帧',
            'route' => 'video/cut-image',
            'icon' => '',
            'params' => []
        ],
        [
            'title' => 'Excel导入数据',
            'route' => 'excel/index',
            'icon' => '',
            'params' => [

            ]
        ],
    ],
];