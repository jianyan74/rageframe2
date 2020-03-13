<?php

namespace addons\Merchants\merapi\modules\v1\controllers;

use Yii;
use merapi\controllers\OnAuthController;

/**
 * Class DefaultController
 * @package addons\Merchants\merapi\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    public $modelClass = '';

    /**
    * 不用进行登录验证的方法
    *
    * 例如： ['index', 'update', 'create', 'view', 'delete']
    * 默认全部需要验证
    *
    * @var array
    */
    protected $authOptional = ['index'];

    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        return 'Hello world';
    }
}