<?php
namespace addons\RfArticle\api\controllers;

use addons\RfArticle\common\models\ArticleCate;
use api\controllers\OnAuthController;

/**
 * 文章分类
 *
 * Class ArticleCateController
 * @package addons\RfArticle\api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleCateController extends OnAuthController
{
    public $modelClass = ArticleCate::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = ['index'];

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'create', 'update', 'view'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}