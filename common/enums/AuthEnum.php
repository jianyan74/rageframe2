<?php

namespace common\enums;

/**
 * Class AuthEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AuthEnum
{
    const TYPE_BACKEND = 'backend';
    const TYPE_FRONTEND = 'frontend';
    const TYPE_API = 'api';
    const TYPE_WECHAT = 'wechat';
    const TYPE_OAUTH2 = 'oauth2';
    const TYPE_STORAGE = 'storage';

    /**
     * @var array
     */
    public static $typeExplain = [
        self::TYPE_BACKEND => '后台',
        self::TYPE_FRONTEND => '前台',
        self::TYPE_API => '接口',
        self::TYPE_WECHAT => '微信',
        self::TYPE_OAUTH2 => 'oauth2',
        self::TYPE_STORAGE => '存储',
    ];

    const TYPE_CHILD_DEFAULT = 'default';
    const TYPE_CHILD_ADDONS = 'addons';

    /**
     * @var array
     */
    public static $typeChildExplain = [
        self::TYPE_CHILD_DEFAULT => '默认',
        self::TYPE_CHILD_ADDONS => '插件',
    ];
}