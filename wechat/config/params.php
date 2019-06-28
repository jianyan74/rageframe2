<?php
return [
    /** ------ 日志记录 ------ **/
    'user.log' => false,
    'user.log.level' => ['warning', 'error'], // 级别 ['info', 'warning', 'error']
    'user.log.noPostData' => [ // 安全考虑,不接收Post存储到日志的路由
        'site/login',
    ],
    'user.log.except.code' => [], // 不记录的code

    /** ------ 非微信打开的时候是否开启微信模拟数据 ------ **/
    'simulateUser' => [
        'switch' => false,// 微信应用模拟用户检测开关
        'userInfo' => [
            'id' => 'oW6qtS0fitZTWHudEX-7ik',
            'nickname' => '简言',
            'name' => '简言',
            'avatar' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4eoQGHDIsK05kWV5deHKK99ka7d65eecJZ7CRZGTlicuaoH7YzcbzYXo1pDR6N77bdLTwA6F2mZA1cFw7icJxwwSWbVgqk3l6gU/0',
            'original' => [
                'openid' => 'oW6qtS0fitZTWHudEX-7ik',
                'nickname' => '简言',
                'sex' => 1,
                'language' => 'zh_CN',
                'city' => '杭州',
                'province' => '浙江',
                'country' => '中国',
                'headimgurl' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4eoQGHDIsK05kWV5deHKK99ka7d65eecJZ7CRZGTlicuaoH7YzcbzYXo1pDR6N77bdLTwA6F2mZA1cFw7icJxwwSWbVgqk3l6gU/0',
                'privilege' => [],
            ],
            'token' => '10_8ZUhjEP6s_nanE37Z7Zh3kFRA7ZhFRAALBtkCV1WE',
            'provider' => 'WeChat',
        ],
    ],

    /** ------ 当前的微信用户信息 ------ **/
    'wechatMember' => [],
];
