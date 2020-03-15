<?php

namespace backend\modules\member\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use common\enums\StatusEnum;
use common\models\member\RechargeConfig;
use backend\controllers\BaseController;

/**
 * Class RechargeConfig
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RechargeConfigController extends BaseController
{
    use MerchantCurd;

    /**
     * @var RechargeConfig
     */
    public $modelClass = RechargeConfig::class;

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
                'price' => SORT_ASC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
