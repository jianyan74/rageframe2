<?php

namespace frontend\modules\member\controllers;

/**
 * Default controller for the `member` module
 */
class DefaultController extends MController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
