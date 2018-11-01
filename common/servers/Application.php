<?php
namespace common\servers;

/**
 * 服务配置类
 *
 * Class Application
 * @package common\servers
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'example' => [
            'class' => 'common\servers\example\Example',
            // 子服务
            'childService' => [
                'rule' => [
                    'class' => 'common\servers\example\rule\Rule',
                ],
            ],
        ],
    ];
}