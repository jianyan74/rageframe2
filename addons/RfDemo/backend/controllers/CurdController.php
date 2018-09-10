<?php
namespace addons\RfDemo\backend\controllers;

use yii;
use common\controllers\AddonsBaseController;
use addons\RfDemo\common\models\Curd;

/**
 * curd
 *
 * Class CurdController
 * @package addons\RfDemo\backend\controllers
 */
class CurdController extends AddonsBaseController
{

    public function actionIndex()
    {
        return $this->render('index', [
            'models' => new Curd()
        ]);
    }
}