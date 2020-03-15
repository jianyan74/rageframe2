<?php

namespace addons\Merchants\merchant\modules\base\controllers;

use Yii;
use common\helpers\AddonHelper;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\models\merchant\CommissionWithdraw;
use addons\Merchants\merchant\controllers\BaseController;
use addons\Merchants\common\models\forms\CommissionWithdrawForm;
use addons\Merchants\common\models\SettingForm;

/**
 * Class PromoterCommissionWithdrawController
 * @package addons\TinyDistribution\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CommissionWithdrawController extends BaseController
{
    use MerchantCurd;

    /**
     * @var CommissionWithdraw
     */
    public $modelClass = CommissionWithdraw::class;

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
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 提现申请
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionApply()
    {
        $config = new SettingForm();
        $config->attributes = AddonHelper::getBackendConfig();

        $defaultBankAccount = Yii::$app->services->merchantBankAccount->findDefault();
        $model = new CommissionWithdrawForm();
        $model->merchant_id = Yii::$app->user->identity->merchant_id;
        $model->merchant = Yii::$app->services->merchant->findById($model->merchant_id);
        $model->bank_account_id = $defaultBankAccount->id ?? '';
        $model->config = $config;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // 开启事务
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                $transaction->commit();

                return $this->redirect(Yii::$app->request->referrer);
            } catch (\Exception $e) {
                $transaction->rollBack();

                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'bankAccount' => Yii::$app->services->merchantBankAccount->getMapList(),
        ]);
    }
}