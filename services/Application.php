<?php
namespace services;

use common\components\Service;

/**
 * Class Application
 * @package services
 *
 * @property \services\sys\SysService $sys 系统
 * @property \services\member\MemberService $member 用户
 * @property \services\common\SmsService $sms 发送短信
 * @property \services\common\ErrorLogService $errorLog 报错日志记录
 * @property \services\common\MailerService $mailer 发送邮件
 * @property \services\common\PushService $push app推送
 * @property \services\common\ProvincesService $provinces 省市区
 * @property \services\merchant\MerchantService $merchant 商户
 * @author jianyan74 <751393839@qq.com>
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'sys' => [
            'class' => 'services\sys\SysService',
            'childService' => [
                'auth' => [ // 权限
                    'class' => 'services\sys\AuthService',
                ],
                'addon' => [ // 插件
                    'class' => 'services\sys\AddonService',
                ],
                'addonAuth' => [ // 插件权限
                    'class' => 'services\sys\AddonAuthService',
                ],
                'notify' => [ // 消息
                    'class' => 'services\sys\NotifyService',
                ],
            ],
        ],
        'merchant' => [
            'class' => 'services\merchant\MerchantService',
        ],
        'member' => [
            'class' => 'services\member\MemberService',
        ],
        'sms' => [
            'class' => 'services\common\SmsService',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
        ],
        'mailer' => [
            'class' => 'services\common\MailerService',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
        ],
        'errorLog' => [
            'class' => 'services\common\ErrorLogService',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
            'exceptCode' => [403] // 除了数组内的状态码不记录，其他按照配置记录
        ],
        'provinces' => [
            'class' => 'services\common\ProvincesService',
        ],
        'push' => [
            'class' => 'services\common\PushService',
        ],
    ];
}