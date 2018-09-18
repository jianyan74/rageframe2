<?php
namespace addons\RfArticle\frontend\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfArticle\frontend\controllers
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