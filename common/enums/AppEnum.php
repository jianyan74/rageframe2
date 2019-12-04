<?php

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AppEnum extends BaseEnum
{
    const BACKEND = 'backend';
    const FRONTEND = 'frontend';
    const API = 'api';
    const HTML5 = 'html5';
    const MERCHANT = 'merchant';
    const OAUTH2 = 'oauth2';
    const STORAGE = 'storage';
    const CONSOLE = 'console';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::BACKEND => '总后台',
            self::FRONTEND => '前台',
            self::API => '接口',
            self::HTML5 => '手机',
            self::MERCHANT => '商家',
            self::OAUTH2 => 'oauth2',
            self::STORAGE => '存储',
            self::CONSOLE => '控制台',
        ];
    }
}