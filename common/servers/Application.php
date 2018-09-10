<?php
namespace common\servers;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * 服务配置类
 *
 * Class Application
 * @package common\servers
 */
class Application extends BaseObject
{
    /**
     * @var array
     */
    public $providers = [
        'wechat' => [
            'class' => 'common\servers\wechat\Wechat',
            // 子服务
            'childService' => [
                'rule' => [
                    'class' => 'common\servers\wechat\rule\Rule',
                ],
            ],
        ],
    ];

    /**
     * @var
     */
    protected $_service;

    /**
     * 获取服务类
     *
     * @param $serviceName
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getService($serviceName)
    {
        if (!isset($this->_service[$serviceName]))
        {
            if (isset($this->providers[$serviceName]))
            {
                $service = $this->providers[$serviceName];
                $this->_service[$serviceName] = Yii::createObject($service);
            }
            else
            {
                throw new InvalidConfigException('Service [' . $serviceName . '] is not find in ' . get_called_class() . ', you must config it! ');
            }
        }

        return $this->_service[$serviceName];
    }

    /**
     * @param $attr
     * @return mixed
     * @throws InvalidConfigException
     */
    public function __get($attr)
    {
        return $this->getService($attr);
    }
}