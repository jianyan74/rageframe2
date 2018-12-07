<?php
namespace api\controllers;

use Yii;
use yii\filters\Cors;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;

/**
 * 无需授权访问基类
 *
 * Class AController
 * @package api\controllers
 */
class OffAuthController extends \yii\rest\ActiveController
{
    /**
     * 普通获取每页数量
     *
     * @var int
     */
    protected $pageSize = 10;

    /**
     * 启始位移
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * 获取每页数量
     *
     * @var
     */
    protected $limit;

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // 跨域支持
        $behaviors['class'] = Cors::className();

        return $behaviors;
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        // 权限方法检查，如果用了rbac，请注释掉
        $this->checkAccess($action->id, $this->modelClass, Yii::$app->request->get());

        // 分页
        $page = Yii::$app->request->get('page', 1);
        $this->limit = Yii::$app->request->get('per-page', $this->pageSize);
        $this->limit > 100 && $this->limit = 100;
        $this->offset = ($page - 1) * $this->pageSize;

        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        // 自定义数据indexDataProvider覆盖IndexAction中的prepareDataProvider()方法
        // $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
        return $actions;
    }

    /**
     * @return array
     */
    protected function verbs()
    {
        // 判断是否插件模块进入
        if (isset(Yii::$app->params['addon']))
        {
            return [];
        }

        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->orderBy('id desc')
                ->asArray(),
            'pagination' => [
                'pageSize' => Yii::$app->params['user.pageSize'],
                'validatePage' => false,// 超出分页不返回data
            ],
        ]);
    }

    /**
     * 创建
     *
     * @return bool
     */
    public function actionCreate()
    {
        $model = new $this->modelClass();
        $model->attributes = Yii::$app->request->post();
        $model->member_id = Yii::$app->user->identity->member_id;
        if (!$model->save())
        {
            return ResultDataHelper::api(422, $this->analyErr($model->getFirstErrors()));
        }

        return $model;
    }

    /**
     * 更新
     *
     * @param $id
     * @return bool|mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attributes = Yii::$app->request->post();
        if (!$model->save())
        {
            return ResultDataHelper::api(422, $this->analyErr($model->getFirstErrors()));
        }

        return $model;
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::DELETE;
        return $model->save();
    }

    /**
     * 单个显示
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (empty($id) || !($model = $this->modelClass::find()->where(['id' => $id, 'status' => StatusEnum::ENABLED])->one()))
        {
            throw new NotFoundHttpException('请求的数据不存在.');
        }

        return $model;
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    public function analyErr($firstErrors)
    {
        return Yii::$app->debris->analyErr($firstErrors);
    }
}