<?php
namespace backend\modules\sys\controllers;

use yii\data\Pagination;
use common\models\common\PayLog;
use common\models\sys\ActionLog;
use common\models\api\Log as ApiLog;

/**
 * 日志控制器
 *
 * Class LogController
 * @package backend\modules\sys\controllers
 */
class LogController extends SController
{
    /**
     * api日志
     *
     * @return string
     */
    public function actionApi()
    {
        $data = ApiLog::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * api日志详情
     *
     * @param $id
     * @return string
     */
    public function actionApiView($id)
    {
        $model = ApiLog::find()->where(['id' => $id])->one();
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 行为日志
     *
     * @return string
     */
    public function actionAction()
    {
        $data = ActionLog::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionActionView($id)
    {
        $model = ActionLog::find()->where(['id' => $id])->one();
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 支付日志
     *
     * @return string
     */
    public function actionPay()
    {
        $data = PayLog::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 支付日志详情
     *
     * @param $id
     * @return string
     */
    public function actionPayView($id)
    {
        $model = PayLog::find()->where(['id' => $id])->one();
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}