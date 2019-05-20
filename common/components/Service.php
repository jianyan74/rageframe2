<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class Service
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Service extends BaseObject
{
    /**
     * 子服务
     *
     * @var
     */
    public $childService;

    /**
     * @var
     */
    protected $_childService;

    /**
     * 得到services 里面配置的子服务childService的实例
     *
     * @param $childServiceName
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getChildService($childServiceName)
    {
        if (!isset($this->_childService[$childServiceName]))
        {
            $childService = $this->childService;
            if (!isset($childService[$childServiceName]))
            {
                throw new InvalidConfigException('Child Service [' . $childServiceName . '] is not find in ' . get_called_class() . ', you must config it! ');
            }

            $service = $childService[$childServiceName];
            $this->_childService[$childServiceName] = Yii::createObject($service);
        }

        return $this->_childService[$childServiceName];
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