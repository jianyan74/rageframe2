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
    const LOG_SUCCESS = 'log_success';
    const LOG_INFO = 'log_info';
    const LOG_WARNING = 'log_warning';
    const LOG_ERROR = 'log_error';

    /** @var string 短信发送失败 */
    const SMS_ERROR = 'sms_error';

    /**
     * @var array
     */
    public static $listExplain = [
        self::BEHAVIOR_WARNING => '行为警告',
        self::BEHAVIOR_ERROR => '行为异常',
        self::SMS_ERROR => '短信发送异常',
        self::LOG_WARNING => '请求警告',
        self::LOG_ERROR => '请求错误',
    ];
}