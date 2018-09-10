<?php
namespace addons\RfExample\api\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfExample\api\controllers
 */
class IndexController extends AddonsBaseController
{
    /**
    * 首页
    */
    public function actionIndex()
    {
        return Yii::$app->params['addonInfo']['name'] . ' api demo';
    }
}
            