<?php

namespace backend\modules\member\controllers;

use Yii;
use common\models\base\SearchModel;
use common\models\member\CreditsLog;
use common\enums\StatusEnum;
use backend\controllers\BaseController;

/**
 * Class CreditsLogController
 * @package backend\modules\member\controllers
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
            'partialMatchAttributes' => ['realname', 'mobile', 'member_id'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['credit_type' => CreditsLog::CREDIT_TYPE_CONSUME_MONEY])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with('member');

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
            'partialMatchAttributes' => ['realname', 'mobile', 'member_id'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'credit_type', [CreditsLog::CREDIT_TYPE_USER_MONEY, CreditsLog::CREDIT_TYPE_GIVE_MONEY]])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with('member');

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
            'partialMatchAttributes' => ['realname', 'mobile', 'member_id'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['in', 'credit_type', [CreditsLog::CREDIT_TYPE_USER_INTEGRAL, CreditsLog::CREDIT_TYPE_GIVE_INTEGRAL]])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with('member');

        return $this->render('credit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => '积分日志'
        ]);
    }
}