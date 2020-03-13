<?php

namespace backend\modules\member\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\models\member\Member;
use common\enums\StatusEnum;
use backend\controllers\BaseController;
use backend\modules\member\forms\RechargeForm;

/**
 * 会员管理
 *
 * Class MemberController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends BaseController
{
    use MerchantCurd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Member::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['account', 'level']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $model->merchant_id = !empty($this->getMerchantId()) ? $this->getMerchantId() : 0;
        $model->scenario = 'backendCreate';
        $modelInfo = clone $model;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            // 验证密码
            if ($modelInfo['password_hash'] != $model->password_hash) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
            }

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 积分/余额变更
     *
     * @param $id
     * @return mixed|string
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRecharge($id)
    {
        $rechargeForm = new RechargeForm();
        $member = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($rechargeForm);
        if ($rechargeForm->load(Yii::$app->request->post())) {
            if (!$rechargeForm->save($member)) {
                return $this->message($this->getError($rechargeForm), $this->redirect(['index']), 'error');
            }

            return $this->message('充值成功', $this->redirect(['index']));
        }

        return $this->renderAjax($this->action->id, [
            'model' => $member,
            'rechargeForm' => $rechargeForm,
        ]);
    }
}