<?php

namespace oauth2\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class SiteController
 * @package oauth2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends Controller
{
    /**
     * @return string
     */
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        return $exception->getMessage();
    }
}
