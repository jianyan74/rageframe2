<?php

namespace backend\modules\common\controllers;

use Yii;
use common\helpers\EchantsHelper;
use common\helpers\ResultDataHelper;
use common\models\common\Log;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
 * Class LogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class LogController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Log::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['method', 'url'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param string $data
     * @return array|string
     */
    public function actionStat($type = '')
    {
        if (!empty($type)) {
            $data = Yii::$app->services->log->stat($type);
            return ResultDataHelper::json(200, '获取成功', $data);
        }

        return $this->renderAjax($this->action->id, [

        ]);
    }

    /**
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = Log::find()
            ->where(['id' => $id])
            ->one();

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}