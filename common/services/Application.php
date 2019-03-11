<?php
namespace common\services;

/**
 * 服务配置类
 *
 * Class Application
 * @package common\services
 *
 * @property \common\services\sys\Sys $sys 系统
 * @property \common\services\member\Member $member 用户
 * @property \common\services\common\Sms $sms 发送短信
 * @property \common\services\common\ErrorLog $errorLog 报错日志记录
 * @property \common\services\common\Mailer $mailer 发送邮件
 * @property \common\services\common\Push $push app推送
 * @property \common\services\common\Provinces $provinces 省市区
 * @author jianyan74 <751393839@qq.com>
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'sys' => [
            'class' => 'common\services\sys\Sys',
            'childService' => [
                'auth' => [ // 权限
                    'class' => 'common\services\sys\Auth',
                ],
                'addon' => [ // 插件
                    'class' => 'common\services\sys\Addon',
                ],
                'notify' => [ // 消息
                    'class' => 'common\services\sys\Notify',
                ],
            ],
        ],
        'member' => [
            'class' => 'common\services\member\Member',
        ],
        'sms' => [
            'class' => 'common\services\common\Sms',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
        ],
        'mailer' => [
            'class' => 'common\services\common\Mailer',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
        ],
        'errorLog' => [
            'class' => 'common\services\common\ErrorLog',
            'queueSwitch' => false, // 是否丢进队列 注意如果需要请先开启执行队列
            'exceptCode' => [403] // 除了数组内的状态码不记录，其他按照配置记录
        ],
        'provinces' => [
            'class' => 'common\services\common\Provinces',
        ],
        'push' => [
            'class' => 'common\services\common\Push',
        ],
    ];
}