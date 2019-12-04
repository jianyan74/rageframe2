<?php

return [
    'adminEmail' => 'admin@example.com',
    'adminAcronym' => 'RF',
    'adminTitle' => 'RageFrame',
    'adminDefaultHomePage' => ['main/system'], // 默认主页

    /** ------ 总管理员配置 ------ **/
    'adminAccount' => 1,// 系统管理员账号id
    'isMobile' => false, // 手机访问
    // 多商户
    'merchantOpen' => false,

    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.noPostData' => [ // 安全考虑,不接收Post存储到日志的路由
        'backend/site/login',
        'base/member/up-password',
        'base/member/ajax-edit',
        'member/member/ajax-edit',
    ],
    'user.log.except.code' => [404], // 不记录的code

    /** ------ 开发者信息 ------ **/
    'exploitDeveloper' => '简言',
    'exploitFullName' => 'RageFrame应用开发引擎',
    'exploitOfficialWebsite' => '<a href="http://www.rageframe.com" target="_blank">www.rageframe.com</a>',
    'exploitGitHub' => '<a href="https://github.com/jianyan74/rageframe2" target="_blank">https://github.com/jianyan74/rageframe2</a>',

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