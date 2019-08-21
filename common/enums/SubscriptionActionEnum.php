<?php

namespace common\enums;

/**
 * Class SubscriptionActionEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SubscriptionActionEnum
{
    /** @var string 行为提醒 隶属行为 */
    const BEHAVIOR_INFO = 'behavior_info';
    const BEHAVIOR_WARNING = 'behavior_warning';
    const BEHAVIOR_ERROR = 'behavior_error';

    /** @var string 日志提醒 隶属日志  */
    const LOG_INFO = 'behavior_info';
    const LOG_WARNING = 'log_warning';
    const LOG_ERROR = 'log_error';

    /**
     * @var array
     */
    public static $defaultList = [
        self::BEHAVIOR_INFO => 0,
        self::BEHAVIOR_WARNING => 0,
        self::BEHAVIOR_ERROR => 0,
        self::LOG_INFO => 0,
        self::LOG_WARNING => 0,
        self::LOG_ERROR => 0,
    ];
}