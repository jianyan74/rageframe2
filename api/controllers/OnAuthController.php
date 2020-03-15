<?php

namespace api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;

/**
 * 需要授权登录访问基类
 *
 * Class OnAuthController
 * @package api\controllers
 * @property yii\db\ActiveRecord|yii\base\Model $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class OnAuthController extends ActiveController
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
     * 首页
     *
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->orderBy('id desc')
                ->asArray(),
            'pagination' => [
                'pageSize' => $this->pageSize,
                'validatePage' => false,// 超出分页不返回data
            ],
        ]);
    }

    /**
     * 创建
     *
     * @return mixed|\yii\db\ActiveRecord
     */
    public function actionCreate()
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass();
        $model->attributes = Yii::$app->request->post();
        $model->member_id = Yii::$app->user->identity->member_id;
        $model->merchant_id = Yii::$app->user->identity->merchant_id;
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return $model;
    }

    /**
     * 更新
     *
     * @param $id
     * @return mixed|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attributes = Yii::$app->request->post();
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return $model;
    }

    /**
     * 删除
     *
     * @param $id
     * @return bool
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
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * @param $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || !($model = $this->modelClass::find()->where([
                'id' => $id,
                'status' => StatusEnum::ENABLED,
            ])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one())) {
            throw new NotFoundHttpException('请求的数据不存在');
        }

        return $model;
    }
}
