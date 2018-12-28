<?php
namespace api\modules\v1\controllers;

use Yii;
use api\controllers\OffAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass;
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

    /**
     * 测试查询方法
     *
     * @return string
     */
    public function actionSearch()
    {
        return '测试查询';
    }
}
