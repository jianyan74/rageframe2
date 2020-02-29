<?php

namespace addons\Merchants\oauth2\modules\v1\controllers;

use Yii;
use oauth2\controllers\OnAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\Merchants\oauth2\modules\v1\controllers
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