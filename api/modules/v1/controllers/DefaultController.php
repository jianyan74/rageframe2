<?php

namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
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
    protected $authOptional = ['index', 'search'];

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
     * 注意：该方法在 main.php 文件里面的 extraPatterns 单独配置过才正常访问
     *
     * @return string
     */
    public function actionSearch()
    {
        return '测试查询';
    }
}
