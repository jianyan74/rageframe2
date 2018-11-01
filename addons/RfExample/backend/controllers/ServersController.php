<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use common\controllers\AddonsBaseController;

/**
 * Class ServersController
 * @package addons\RfExample\backend\controllers
 */
class ServersController extends AddonsBaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'service' => Yii::$app->servers->example->index(),
            'childService' => Yii::$app->servers->example->rule->index('childService'),
            'serviceToChildService' => Yii::$app->servers->example->child(),
        ]);
    }
}