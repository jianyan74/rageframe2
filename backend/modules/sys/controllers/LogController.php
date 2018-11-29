<?php
namespace backend\modules\sys\controllers;

use Yii;
use yii\data\Pagination;
use common\models\common\PayLog;
use common\models\sys\ActionLog;
use common\models\common\Log;

/**
 * 日志控制器
 *
 * Class LogController
 * @package backend\modules\sys\controllers
 */
class LogController extends SController
{
    /**
     * 报错日志
     *
     * @return string
     */
    public function actionError()
    {
        $error_code = Yii::$app->request->get('error_code', null);
        $where = [];
        $error_code == 1 && $where = ['<', 'error_code', 299];
        $error_code == 2 && $where = ['>', 'error_code', 299];

        $data = Log::find()->filterWhere($where);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'error_code' => $error_code,
        ]);
    }

    /**
     * 报错日志详情
     *
     * @param $id
     * @return string
     */
    public function actionErrorView($id)
    {
        $model = Log::find()->where(['id' => $id])->one();
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
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
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
        $pay_status = Yii::$app->request->get('pay_status', null);
        $keyword = Yii::$app->request->get('keyword', null);

        $data = PayLog::find()
            ->filterWhere(['pay_status' => $pay_status])
            ->orFilterWhere(['like', 'order_sn', $keyword])
            ->orFilterWhere(['like', 'out_trade_no', $keyword]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'pay_status' => $pay_status,
            'keyword' => $keyword,
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
        return $this->renderAjax($this->action->id, [
            'model' => PayLog::find()->where(['id' => $id])->one(),
        ]);
    }
}