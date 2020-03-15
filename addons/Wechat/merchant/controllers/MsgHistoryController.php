<?php

namespace addons\Wechat\merchant\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use addons\Wechat\common\models\MsgHistory;
use addons\Wechat\common\models\Rule;

/**
 * 微信历史消息
 *
 * Class MsgHistoryController
 * @package addons\Wechat\merchant\controllers
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
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->with(['fans', 'rule']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'moduleExplain' => Rule::$moduleExplain,
        ]);
    }
}