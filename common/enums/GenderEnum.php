<?php

namespace common\enums;

/**
 * 性别枚举
 *
 * Class GenderEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class GenderEnum
{
    const UNKNOWN = 0;
    const MAN = 1;
    const WOMAN = 2;

    /**
     * @var array
     */
    public static $listExplain = [
        self::MAN => '男',
        self::WOMAN => '女',
        self::UNKNOWN => '未知',
    ];
}