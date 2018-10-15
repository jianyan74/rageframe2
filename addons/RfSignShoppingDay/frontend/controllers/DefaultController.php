<?php
namespace addons\RfSignShoppingDay\frontend\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfSignShoppingDay\frontend\controllers
 */
class DefaultController extends AddonsBaseController
{
    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }
}