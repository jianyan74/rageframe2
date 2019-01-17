<?php
namespace addons\RfArticle\wechat\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class DefaultController
 * @package addons\RfArticle\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
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