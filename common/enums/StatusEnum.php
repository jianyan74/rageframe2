<?php
namespace common\enums;

/**
 * 状态枚举
 *
 * Class StatusEnum
 * @package common\enum
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
