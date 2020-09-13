<?php

namespace backend\modules\common\controllers;

use Yii;
use common\helpers\ResultHelper;
use common\models\common\Log;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use yii\data\Pagination;

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
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->with(['member']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIpStatistics()
    {
        $ip = Yii::$app->request->get('ip');
        $start_time = Yii::$app->request->get('start_time', date('Y-m-d', strtotime("-10 day")));
        $end_time = Yii::$app->request->get('end_time', date('Y-m-d', strtotime("+1 day")));

        $data = Log::find()
            ->select(['ip', 'count(id) as count'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['between', 'created_at', strtotime($start_time), strtotime($end_time)])
            ->andFilterWhere(['ip' => $ip])
            ->groupBy(['ip']);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('count desc, ip desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'ip' => $ip,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
    }

    /**
     * @return string
     */
    public function actionStatistics()
    {
        return $this->render($this->action->id, [

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

            return ResultHelper::json(200, '获取成功', $data);
        }

        return $this->renderAjax($this->action->id, [

        ]);
    }

    /**
     * @param string $data
     * @return array|string
     */
    public function actionFlowStat($type = '')
    {
        if (!empty($type)) {
            $data = Yii::$app->services->log->flowStat($type);

            return ResultHelper::json(200, '获取成功', $data);
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
        return $this->renderAjax($this->action->id, [
            'model' => Log::findOne($id),
        ]);
    }
}