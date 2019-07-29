<?php

namespace addons\RfMerchants\api\controllers\v1;

use Yii;
use api\controllers\OnAuthController;
use api\controllers\UserAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfMerchants\api\controllers\v1 */
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
    protected $optional = ['index'];

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