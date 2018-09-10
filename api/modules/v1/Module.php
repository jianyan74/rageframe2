<?php
namespace api\modules\v1;

use Yii;

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
