<?php
namespace addons\RfDemo\frontend\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfDemo\frontend\controllers
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
            