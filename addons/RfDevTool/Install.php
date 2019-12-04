<?php

namespace addons\RfDevTool;

use common\helpers\MigrateHelper;
use common\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfDevTool
 */
class Install implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run($addon)
    {
        MigrateHelper::upByPath([
            '@addons/RfDevTool/console/migrations/'
        ]);
    }
}