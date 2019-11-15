<?php

namespace common\enums;

/**
 * Class TypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TypeEnum extends BaseEnum
{
    const TYPE_DEFAULT = 'default';
    const TYPE_ADDONS = 'addons';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::TYPE_DEFAULT => '默认',
            self::TYPE_ADDONS => '插件',
        ];
    }
}