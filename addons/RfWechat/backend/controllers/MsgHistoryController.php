<?php

namespace addons\RfWechat\backend\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\components\MerchantCurd;
use addons\RfWechat\common\models\MsgHistory;
use addons\RfWechat\common\models\Rule;


/**
 * 微信历史消息
 *
 * Class MsgHistoryController
 * @package addons\RfWechat\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MsgHistoryController extends BaseController
{
    use MerchantCurd;

    /**
     * @var MsgHistory
     */
    public $modelClass = MsgHistory::class;

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
            'partialMatchAttributes' => ['message'], // 模糊查询
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
            ->with(['fans', 'rule']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'moduleExplain' => Rule::$moduleExplain,
        ]);
    }
}