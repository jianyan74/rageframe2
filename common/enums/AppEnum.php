<?php

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AppEnum
{
    const BACKEND = 'backend';
    const FRONTEND = 'frontend';
    const API = 'api';
    const WECHAT = 'wechat';
    const OAUTH2 = 'oauth2';
    const STORAGE = 'storage';
    const CONSOLE = 'console';

    /**
     * @var array
     */
    public static $listExplain = [
        self::BACKEND => '后台',
        self::FRONTEND => '前台',
        self::API => '接口',
        self::WECHAT => '微信',
        self::OAUTH2 => 'oauth2',
        self::STORAGE => '存储',
        self::CONSOLE => '控制台',
    ];
}