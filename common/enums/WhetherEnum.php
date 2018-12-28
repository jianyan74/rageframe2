<?php
namespace common\enums;

/**
 * Class WhetherEnum
 * @package common\enums
 */
class WhetherEnum
{
    const ENABLED = 1;
    const DISABLED = 0;

    /**
     * @var array
     */
    public static $listExplain = [
        self::ENABLED => '是',
        self::DISABLED => '否',
    ];
}
