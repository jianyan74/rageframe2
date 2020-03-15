<?php

namespace addons\RfExample\common\components;

use Yii;
use common\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\RfExample\common\config
 */
class Bootstrap implements AddonWidget
{
    /**
    * @param $addon
    * @return mixed|void
    */
    public function run($addon)
    {
        Yii::$app->services->merchant->addId(0);
    }
}