<?php

namespace addons\RfDevTool;

use Yii;
use common\helpers\MigrateHelper;
use common\interfaces\AddonWidget;

/**
 * 卸载
 *
 * Class UnInstall
 * @package addons\RfArticle
 */
class UnInstall implements AddonWidget
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
        MigrateHelper::downByPath([
            '@addons/RfDevTool/console/migrations/'
        ]);
    }
}