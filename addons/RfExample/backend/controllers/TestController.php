<?php
namespace addons\RfExample\backend\controllers;

use common\controllers\AddonsBaseController;

/**
 * Class TestController
 * @package addons\RfExample\backend\controllers
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