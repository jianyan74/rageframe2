<?php

namespace merapi\modules\v2\controllers;

use Yii;
use merapi\controllers\OnAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package merapi\modules\v2\controllers
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
    protected $authOptional = ['index'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return 'v2 default index';
    }
}
