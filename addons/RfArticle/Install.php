<?php

namespace addons\RfArticle;

use common\helpers\MigrateHelper;
use backend\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfArticle
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
            '@addons/RfArticle/console/migrations/'
        ]);
    }
}