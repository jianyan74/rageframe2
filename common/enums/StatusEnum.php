<?php
namespace common\enums;

/**
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StatusEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETE = -1;

    /**
     * @var array
     */
    public static $listExplain = [
        self::ENABLED => '启用',
        self::DISABLED => '禁用',
    ];
}
