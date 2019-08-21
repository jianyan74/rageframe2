<?php

namespace common\enums;

/**
 * Class SubscriptionReasonEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SubscriptionReasonEnum
{
    // 提醒关联的目标类型组别

    /**
     * 行为创建
     */
    const BEHAVIOR_CREATE = 'behavior_create';

    /**
     * 日志创建
     */
    const LOG_CREATE = 'log_create';

    // 订阅原因对应订阅事件
    public static $reasonAction = [
        self::BEHAVIOR_CREATE => [SubscriptionActionEnum::BEHAVIOR_WARNING, SubscriptionActionEnum::BEHAVIOR_ERROR], // 行为创建(异常、警告)
        self::LOG_CREATE => [SubscriptionActionEnum::LOG_WARNING, SubscriptionActionEnum::LOG_ERROR], // 请求创建(异常、警告)
    ];
}