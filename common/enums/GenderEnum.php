<?php

namespace common\enums;

/**
 * 性别枚举
 *
 * Class GenderEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class GenderEnum extends BaseEnum
{
    const UNKNOWN = 0;
    const MAN = 1;
    const WOMAN = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MAN => '男',
            self::WOMAN => '女',
            self::UNKNOWN => '未知',
        ];
    }
}