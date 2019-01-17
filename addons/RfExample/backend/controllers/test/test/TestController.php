<?php
namespace addons\RfExample\backend\controllers\test\test;

use common\controllers\AddonsBaseController;

/**
 * Class TestController
 * @package addons\RfExample\backend\controllers\test\test
 * @author jianyan74 <751393839@qq.com>
 */
class TestController extends AddonsBaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }

    /**
     * @return string
     */
    public function actionUpdate()
    {
        return $this->render('update', [

        ]);
    }
}