<?php
namespace api\modules\v1;

/**
 * Class Module
 * @package api\modules\v1
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function init()
    {
        parent::init();
    }
}
