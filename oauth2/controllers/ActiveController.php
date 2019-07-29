<?php

namespace oauth2\controllers;

use Yii;
use yii\filters\Cors;
use yii\web\BadRequestHttpException;
use oauth2\behaviors\JWTAuth;
use common\components\BaseAction;
use api\behaviors\HttpSignAuth;

/**
 * Class ActiveController
 * @package oauth2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ActiveController extends \yii\rest\ActiveController
{
    use BaseAction;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = [];

    /**
     * 启始位移
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * 实际每页数量
     *
     * @var
     */
    protected $limit;

    /**
     * 行为验证
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // 跨域支持
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        // 进行签名验证
        $behaviors['signTokenValidate'] = [
            'class' => HttpSignAuth::class,
            'switch' => Yii::$app->params['user.httpSignValidity'] // 验证开启状态
        ];

        // 授权验证
        $behaviors['jwtAuth'] = [
            'class' => JWTAuth::class,
            // 不进行认证判断方法
            'optional' => $this->optional,
        ];

        return $behaviors;
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        // 权限方法检查，如果用了rbac，请注释掉
        $this->checkAccess($action->id, $this->modelClass, Yii::$app->request->get());

        // 分页
        $page = Yii::$app->request->get('page', 1);
        $this->limit = Yii::$app->request->get('per-page', $this->pageSize);
        $this->limit > 50 && $this->limit = 50;
        $this->offset = ($page - 1) * $this->pageSize;

        return true;
    }

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
        if (in_array($action, ['update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('您的权限不足，如需要请联系管理员');
        }
    }
}