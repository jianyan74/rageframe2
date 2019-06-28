<?php
namespace oauth2\modules\v1\controllers;

use Yii;
use oauth2\controllers\OnAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package oauth2\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['search'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return Yii::$app->user->identity;
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
