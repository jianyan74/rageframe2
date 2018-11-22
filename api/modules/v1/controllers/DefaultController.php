<?php
namespace api\modules\v1\controllers;

use Yii;
use api\controllers\OffAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 */
class DefaultController extends OffAuthController
{
    public $modelClass = '';

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return 'index';
    }
}
