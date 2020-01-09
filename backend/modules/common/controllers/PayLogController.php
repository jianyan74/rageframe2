<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\common\PayLog;
use backend\controllers\BaseController;

/**
 * Class PayLogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PayLogController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => PayLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['order_sn'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
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
        return $this->renderAjax($this->action->id, [
            'model' => PayLog::findOne($id),
        ]);
    }
}