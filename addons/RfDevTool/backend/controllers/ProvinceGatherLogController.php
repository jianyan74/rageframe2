<?php

namespace addons\RfDevTool\backend\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use addons\RfDevTool\common\queues\ProvinceChildJob;
use addons\RfDevTool\common\models\ProvinceGatherLog;

/**
 * Class ProvinceGatherLogController
 * @package addons\RfDevTool\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ProvinceGatherLogController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $job_id = Yii::$app->request->get('job_id');

        $searchModel = new SearchModel([
            'model' => ProvinceGatherLog::class,
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
            ->andWhere(['job_id' => $job_id])
            ->andWhere(['reconnection' => 0])
            ->andWhere(['status' => StatusEnum::ENABLED]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionRetry($id)
    {
        $model = ProvinceGatherLog::findOne(['id' => $id]);
        $model->status = StatusEnum::DISABLED;
        $model->save();
        $queue = new ProvinceChildJob([
            'parent' => $model->data,
            'baseUrl' => $model->url,
            'maxLevel' => $model->max_level,
            'job_id' => $model->job_id,
            'level' => $model->level,
        ]);

        $messageId = Yii::$app->queue->push($queue);

        return $this->message('推入队列成功，请注意执行', $this->redirect(['index', 'job_id' => $model['job_id']]));
    }
}