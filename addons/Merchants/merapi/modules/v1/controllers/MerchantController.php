<?php

namespace addons\Merchants\merapi\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\ResultHelper;
use common\models\merchant\Merchant;
use api\controllers\OnAuthController;

/**
 * 个人信息
 *
 * Class MerchantController
 * @package addons\Merchants\merapi\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantController extends OnAuthController
{
    /**
     * @var Merchant
     */
    public $modelClass = Merchant::class;

    /**
     * 个人中心
     *
     * @return array|null|\yii\data\ActiveDataProvider|\yii\db\ActiveRecord
     */
    public function actionIndex()
    {
        $merchant_id = Yii::$app->user->identity->merchant_id;

        $member = $this->modelClass::find()
            ->where(['id' => $merchant_id])
            ->with(['account'])
            ->asArray()
            ->one();

        return $member;
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
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        $merchant_id = Yii::$app->user->identity->merchant_id;
        $member = Merchant::find()
            ->where(['id' => $merchant_id])
            ->with(['account'])
            ->asArray()
            ->one();

        return $member;
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
        if (in_array($action, ['delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (empty($id) || !($model = Merchant::find()->where(['id' => Yii::$app->user->identity->merchant_id])->one())) {
            throw new NotFoundHttpException('请求的数据不存在或您的权限不足.');
        }

        return $model;
    }
}