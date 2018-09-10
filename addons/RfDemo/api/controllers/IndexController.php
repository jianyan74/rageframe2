<?php
namespace addons\RfDemo\api\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfDemo\api\controllers
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
            