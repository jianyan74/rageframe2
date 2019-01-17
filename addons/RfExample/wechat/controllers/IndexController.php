<?php
namespace addons\RfExample\wechat\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class IndexController
 * @package addons\RfExample\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
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
            