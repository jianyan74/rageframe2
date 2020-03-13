<?php

namespace addons\Merchants\merchant\modules\base\controllers;

use Yii;
use common\models\base\SearchModel;
use common\models\merchant\CreditsLog;
use common\enums\StatusEnum;
use addons\Merchants\merchant\controllers\BaseController;

/**
 * Class CreditsLogController
 * @package merchant\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogController extends BaseController
{
    /**
     * 消费日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => CreditsLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['credit_type' => CreditsLog::CREDIT_TYPE_CONSUME_MONEY])
            ->with('merchant');

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 余额日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionMoney()
    {
        $searchModel = new SearchModel([
            'model' => CreditsLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['in', 'credit_type', [CreditsLog::CREDIT_TYPE_USER_MONEY, CreditsLog::CREDIT_TYPE_GIVE_MONEY]])
            ->with('merchant');

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '余额日志'
        ]);
    }

    /**
     * 积分日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIntegral()
    {
        $searchModel = new SearchModel([
            'model' => CreditsLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['in', 'credit_type', [CreditsLog::CREDIT_TYPE_USER_INTEGRAL, CreditsLog::CREDIT_TYPE_GIVE_INTEGRAL]])
            ->with('merchant');

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '积分日志'
        ]);
    }
}