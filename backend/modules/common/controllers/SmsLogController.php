<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\base\SearchModel;
use common\models\common\SmsLog;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use backend\controllers\BaseController;

/**
 * Class SmsLogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SmsLogController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => SmsLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['mobile'], // 模糊查询
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
            'model' => SmsLog::findOne($id),
        ]);
    }

    /**
     * @param string $data
     * @return array|string
     */
    public function actionStat($type = '')
    {
        if (!empty($type)) {
            $data = Yii::$app->services->sms->stat($type);

            return ResultHelper::json(200, '获取成功', $data);
        }

        return $this->renderAjax($this->action->id, [

        ]);
    }
}