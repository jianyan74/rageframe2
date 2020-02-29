<?php

namespace addons\Merchants\merchant\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Merchants\merchant\controllers
 */
class DefaultController extends BaseController
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