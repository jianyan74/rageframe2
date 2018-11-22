<?php
namespace api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;

/**
 * 需要授权登录访问基类
 *
 * 适用于个人中心
 * Class UserOnAuthController
 * @package api\controllers
 */
class UserOnAuthController extends ActiveController
{
    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['view'], $actions['delete']);
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
     * 验证更新是否本人
     *
     * @param $action
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if ($action == 'update' && Yii::$app->user->identity->member_id != Yii::$app->request->get('id', null))
        {
            throw new NotFoundHttpException('权限不足.');
        }

        return parent::beforeAction($action);
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['status' => StatusEnum::ENABLED, 'member_id' => Yii::$app->user->identity->member_id])
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
        if (empty($id) || !($model = $this->modelClass::find()->where(['id' => $id, 'status' => StatusEnum::ENABLED, 'member_id' => Yii::$app->user->identity->member_id])->one()))
        {
            throw new NotFoundHttpException('请求的数据不存在或您的权限不足.');
        }

        return $model;
    }
}
