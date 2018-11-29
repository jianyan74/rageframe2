<?php
namespace common\services;

/**
 * 服务配置类
 *
 * Class Application
 * @package common\services
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
        'easySms' => [ // 发送短信
            'class' => 'common\services\common\EasySms',
        ],
        'errorLog' => [ // 报错日志记录
            'class' => 'common\services\common\ErrorLog',
        ],
    ];
}