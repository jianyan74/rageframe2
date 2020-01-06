<?php

namespace addons\RfExample\merchant\controllers\test\test;

use addons\RfExample\merchant\controllers\BaseController;

/**
 * Class TestController
 * @package addons\RfExample\merchant\controllers\test\test
 * @author jianyan74 <751393839@qq.com>
 */
class TestController extends BaseController
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