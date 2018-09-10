<?php
namespace addons\RfExample\frontend\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfExample\frontend\controllers
 */
class IndexController extends AddonsBaseController
{
    /**
    * 首页
    */
    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }
}
            