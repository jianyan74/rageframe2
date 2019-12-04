<?php

return [
    'adminEmail' => 'admin@example.com',
    'adminAcronym' => 'RF',
    'adminTitle' => 'RageFrame - 商户端',
    'adminDefaultHomePage' => ['main/system'], // 默认主页

    // 多商户开启
    'merchantOpen' => true,
    // 登陆后的当前商户信息
    'merchant' => '',

    /** ------ 总管理员配置 ------ **/
    'adminAccount' => '0',// 系统管理员账号id
    'isMobile' => false, // 手机访问

    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.noPostData' => [ // 安全考虑,不接收Post存储到日志的路由
        'merchant/site/login',
        'base/member/up-password',
        'base/member/ajax-edit',
        'member/member/ajax-edit',
    ],
    'user.log.except.code' => [404], // 不记录的code

    /**
     * 不需要验证的路由全称
     *
     * 注意: 前面以绝对路径/为开头
     */
    'noAuthRoute' => [
        '/main/index',// 系统主页
        '/main/system',// 系统首页
    ],
];