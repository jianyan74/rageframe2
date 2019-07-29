<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Service
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Service extends Component
{
    use BaseAction;

    public $childService;

    protected $_childService;

    /**
     * 获取 services 里面配置的子服务 childService 的实例
     *
     * @param $childServiceName
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function getChildService($childServiceName)
    {
        if (!isset($this->_childService[$childServiceName])) {
            $childService = $this->childService;

            if (isset($childService[$childServiceName])) {
                $service = $childService[$childServiceName];
                $this->_childService[$childServiceName] = Yii::createObject($service);
            } else {
                throw new InvalidConfigException('Child Service [' . $childServiceName . '] is not find in ' . get_called_class() . ', you must config it! ');
            }
        }

        return $this->_childService[$childServiceName] ?? null;
    }

    /**
     * @param string $attr
     * @return mixed
     * @throws InvalidConfigException
     */
    public function __get($attr)
    {
        return $this->getChildService($attr);
    }
}