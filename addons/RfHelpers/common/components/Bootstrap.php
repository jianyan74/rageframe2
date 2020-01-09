<?php

namespace addons\RfHelpers\common\components;

use Yii;
use yii\web\UnauthorizedHttpException;
use common\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\RfHelpers\common\config
 */
class Bootstrap implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws UnauthorizedHttpException
     */
    public function run($addon)
    {
        if (YII_ENV_PROD) {
            throw new UnauthorizedHttpException('正式环境禁止访问');
        }
    }
}