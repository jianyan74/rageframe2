<?php

namespace addons\RfDevTool\backend\controllers;

/**
 * Class PhpInfoController
 * @package addons\RfDevTool\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PhpInfoController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }
}