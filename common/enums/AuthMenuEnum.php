<?php

namespace common\enums;

/**
 * Class AuthMenuEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AuthMenuEnum extends BaseEnum
{
    const DEDAULT = 0;
    const LEFT = 1;
    const TOP = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DEDAULT => '默认',
            self::LEFT => '左侧菜单',
            self::TOP => '顶部菜单',
        ];
    }
}