<?php
namespace addons\RfArticle\frontend\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfArticle\frontend\controllers
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