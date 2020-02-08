<?php

return [

    // ----------------------- 菜单配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-wechat',
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '增强功能',
            'name' => 'function',
            'child' => [
                [
                    'title' => '自动回复',
                    'name' => 'autoReply',
                    'child' => [
                        [
                            'title' => '关键字首页',
                            'name' => 'rule/index',
                        ],
                        [
                            'title' => '关键字新增/编辑',
                            'name' => 'rule/edit',
                        ],
                        [
                            'title' => '关键字删除',
                            'name' => 'rule/delete',
                        ],
                        [
                            'title' => '关键字状态修改',
                            'name' => 'rule/ajax-update',
                        ],
                        [
                            'title' => '非文字自动回复',
                            'name' => 'setting/special-message',
                        ],
                        [
                            'title' => '关注/默认回复',
                            'name' => 'reply-default/index',
                        ],
                    ],
                ],
                [
                    'title' => '自定义菜单',
                    'name' => 'menu',
                    'child' => [
                        [
                            'title' => '首页',
                            'name' => 'menu/index',
                        ],
                        [
                            'title' => '新增/编辑',
                            'name' => 'menu/edit',
                        ],
                        [
                            'title' => '删除',
                            'name' => 'menu/delete',
                        ],
                        [
                            'title' => '状态修改',
                            'name' => 'menu/ajax-update',
                        ],
                        [
                            'title' => '同步菜单',
                            'name' => 'menu/sync',
                        ],
                        [
                            'title' => '生效并置顶',
                            'name' => 'menu/save',
                        ],
                    ],
                ],
                [
                    'title' => '二维码/转化链接',
                    'name' => 'qrCode',
                    'child' => [
                        [
                            'title' => '二维码管理',
                            'name' => 'qrCodeManager',
                            'child' => [
                                [
                                    'title' => '首页',
                                    'name' => 'qrcode/index',
                                ],
                                [
                                    'title' => '编辑',
                                    'name' => 'qrcode/edit',
                                ],
                                [
                                    'title' => '新增',
                                    'name' => 'qrcode/add',
                                ],
                                [
                                    'title' => '删除全部',
                                    'name' => 'qrcode/delete-all',
                                ],
                                [
                                    'title' => '下载',
                                    'name' => 'qrcode/down',
                                ],
                            ],
                        ],
                        [
                            'title' => '扫描统计',
                            'name' => 'qrCodeStatistical',
                            'child' => [
                                [
                                    'title' => '首页',
                                    'name' => 'qrcode-stat/index',
                                ],
                                [
                                    'title' => '删除',
                                    'name' => 'qrcode-stat/delete',
                                ],
                            ],
                        ],
                        [
                            'title' => '长链接转二维码',
                            'name' => 'qrCodeLongUrl',
                            'child' => [
                                [
                                    'title' => '首页',
                                    'name' => 'qrcode/long-url',
                                ],
                                [
                                    'title' => '转化链接',
                                    'name' => 'qrcode/qr',
                                ],
                            ],
                        ]
                    ],
                ],
            ],
        ],
        [
            'title' => '粉丝管理',
            'name' => 'fans',
            'child' => [
                [
                    'title' => '粉丝列表',
                    'name' => 'fansManager',
                    'child' => [
                        [
                            'title' => '首页',
                            'name' => 'fans/index',
                        ],
                        [
                            'title' => '获取全部粉丝',
                            'name' => 'fans/sync-all-openid',
                        ],
                        [
                            'title' => '同步全部粉丝',
                            'name' => 'fans/get-all-fans',
                        ],
                        [
                            'title' => '同步',
                            'name' => 'fans/sync',
                        ],
                        [
                            'title' => '标签分组',
                            'name' => 'fans/move-tag',
                        ],
                        [
                            'title' => '发送消息',
                            'name' => 'fans/send-message',
                        ],
                        [
                            'title' => '详情',
                            'name' => 'fans/view',
                        ],
                    ]
                ],
                [
                    'title' => '粉丝标签',
                    'name' => 'fansTags',
                    'child' => [
                        [
                            'title' => '首页',
                            'name' => 'fans-tags/index',
                        ],
                        [
                            'title' => '删除',
                            'name' => 'fans-tags/delete',
                        ],
                        [
                            'title' => '同步',
                            'name' => 'fans-tags/synchro',
                        ],
                    ]
                ],
            ]
        ],
        [
            'title' => '素材库',
            'name' => 'attachment',
            'child' => [
                [
                    'title' => '首页',
                    'name' => 'attachment/index',
                ],
                [
                    'title' => '图文新增/编辑',
                    'name' => 'attachment/news-edit',
                ],
                [
                    'title' => '图片/音频/视频新增',
                    'name' => 'attachment/create',
                ],
                [
                    'title' => '删除',
                    'name' => 'attachment/delete',
                ],
                [
                    'title' => '手机预览',
                    'name' => 'attachment/preview',
                ],
                [
                    'title' => '消息群发',
                    'name' => 'attachment/send',
                ],
                [
                    'title' => '同步',
                    'name' => 'attachment/sync',
                ],
                [
                    'title' => '选择',
                    'name' => 'selector/list',
                ],
            ],
        ],
        [
            'title' => '历史消息',
            'name' => 'msgHistory',
            'child' => [
                [
                    'title' => '首页',
                    'name' => 'msg-history/index',
                ],
                [
                    'title' => '删除',
                    'name' => 'msg-history/delete',
                ],
            ]
        ],
        [
            'title' => '定时群发',
            'name' => 'massRecord',
            'child' => [
                [
                    'title' => '首页',
                    'name' => 'mass-record/index',
                ],
                [
                    'title' => '新增',
                    'name' => 'mass-record/edit',
                ],
                [
                    'title' => '删除',
                    'name' => 'mass-record/delete',
                ],
            ]
        ],
        [
            'title' => '数据统计',
            'name' => 'dataStatistics',
            'child' => [
                [
                    'title' => '粉丝关注统计',
                    'name' => 'stat/fans-follow',
                ],
                [
                    'title' => '回复规则使用量',
                    'name' => 'stat/rule',
                ],
                [
                    'title' => '关键字命中规则',
                    'name' => 'stat/rule-keyword',
                ],
            ],
        ],
        [
            'title' => '参数配置',
            'name' => 'setting/history-stat',
        ],
        [
            'title' => '公用',
            'name' => 'other',
            'child' => [
                [
                    'title' => '图片加载',
                    'name' => 'analysis/image',
                ],
            ],
        ]
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '增强功能',
            'route' => 'function',
            'icon' => 'fa fa-superpowers',
            'child' => [
                [
                    'title' => '自动回复',
                    'route' => 'rule/index',
                ],
                [
                    'title' => '自定义菜单',
                    'route' => 'menu/index',
                ],
                [
                    'title' => '二维码/转化链接',
                    'route' => 'qrcode/index',
                ],
            ],
        ],
        [
            'title' => '粉丝管理',
            'route' => 'fans',
            'icon' => 'fa fa-heart',
            'child' => [
                [
                    'title' => '粉丝列表',
                    'route' => 'fans/index',
                ],
                [
                    'title' => '粉丝标签',
                    'route' => 'fans-tags/index',
                ],
            ],
        ],
        [
            'title' => '素材库',
            'route' => 'attachment/index',
            'icon' => 'fa fa-file',
        ],
        [
            'title' => '历史消息',
            'route' => 'msg-history/index',
            'icon' => 'fa fa-comments',
        ],
        [
            'title' => '定时群发',
            'route' => 'mass-record/index',
            'icon' => 'fa fa-send',
        ],
        [
            'title' => '数据统计',
            'route' => 'dataStatistics',
            'icon' => 'fa fa-pie-chart',
            'child' => [
                [
                    'title' => '粉丝关注统计',
                    'route' => 'stat/fans-follow',
                ],
                [
                    'title' => '回复规则使用量',
                    'route' => 'stat/rule',
                ],
                [
                    'title' => '关键字命中规则',
                    'route' => 'stat/rule-keyword',
                ],
            ],
        ],
        [
            'title' => '参数配置',
            'route' => 'setting/history-stat',
            'icon' => 'fa fa-cog',
        ],
    ],
];