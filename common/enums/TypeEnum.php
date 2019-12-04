<?php

namespace common\enums;

/**
 * Class TypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TypeEnum extends BaseEnum
{
    const DEFAULT = 'default';
    const ADDONS = 'addons';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '默认',
            self::ADDONS => '插件',
        ];
    }
}