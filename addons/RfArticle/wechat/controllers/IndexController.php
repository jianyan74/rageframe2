<?php
namespace addons\RfArticle\wechat\controllers;

use common\controllers\AddonsBaseController;
use common\helpers\AddonUrl;

/**
 * Class IndexController
 * @package addons\RfArticle\wechat\controllers
 */
class IndexController extends AddonsBaseController
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        header('Location:' . AddonUrl::toFront(['index/index']));
    }
}