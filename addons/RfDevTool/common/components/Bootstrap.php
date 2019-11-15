<?php

namespace addons\RfDevTool\common\components;

use Yii;
use common\interfaces\AddonWidget;
use yii\web\UnauthorizedHttpException;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\RfDevTool\common\config
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