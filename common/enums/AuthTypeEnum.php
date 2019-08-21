<?php

namespace common\enums;

/**
 * Class AuthTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AuthTypeEnum
{
    const TYPE_DEFAULT = 'default';
    const TYPE_ADDONS = 'addons';

    /**
     * @var array
     */
    public static $listExplain = [
        self::TYPE_DEFAULT => '默认',
        self::TYPE_ADDONS => '插件',
    ];
}