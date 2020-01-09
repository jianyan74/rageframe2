<?php
return [
    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => YII_DEBUG ? ['success', 'info', 'warning', 'error'] : ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.except.code' => [], // 不记录的code

    /** ------ token相关 ------ **/
    // token有效期是否验证 默认不验证
    'user.accessTokenValidity' => true,
    // token有效期 默认 2 小时
    'user.accessTokenExpire' => 2 * 60 * 60,
    // refresh token有效期是否验证 默认开启验证
    'user.refreshTokenValidity' => true,
    // refresh token有效期 默认30天
    'user.refreshTokenExpire' => 30 * 24 * 60 * 60,
    // 签名验证默认关闭验证，如果开启需了解签名生成及验证
    'user.httpSignValidity' => false,
    // 签名授权公钥秘钥
    'user.httpSignAccount' => [
        'doormen' => 'e3de3825cfbf',
    ],
];
