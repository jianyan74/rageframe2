<?php
return [
    'adminEmail' => 'admin@example.com',

    /** ------ 日志记录 ------ **/
    'user.log' => false,
    'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.except.code' => [], // 不记录的code

    /** ------ token相关 ------ **/

    // 注意：需要了解 DateInterval 来配置时间

    // 设置授权码code过期时间为10分钟
    'user.codeExpire' => 'PT10M',
    // 设置授权码过期时间为1小时
    'user.accessTokenExpire' => 'PT1H',
    // 设置刷新令牌过期时间1个月
    'user.refreshTokenExpire' => 'P1M', //
];
