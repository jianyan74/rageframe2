<?php
namespace addons\RfDemo\wechat\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfDemo\wechat\controllers
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
            