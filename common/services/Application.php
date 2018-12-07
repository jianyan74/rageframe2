<?php
namespace common\services;

/**
 * 服务配置类
 *
 * Class Application
 * @package common\services
 *
 * @property \common\services\member\Member $member
 * @property \common\services\common\Sms $sms
 * @property \common\services\common\ErrorLog $errorLog
 * @property \common\services\common\Mailer $mailer
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'member' => [ // 用户
            'class' => 'common\services\member\Member',
        ],
        'sms' => [ // 发送短信
            'class' => 'common\services\common\Sms',
        ],
        'errorLog' => [ // 报错日志记录
            'class' => 'common\services\common\ErrorLog',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
        ],
        'mailer' => [ // 发送邮件
            'class' => 'common\services\common\Mailer',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
        ],
    ];
}