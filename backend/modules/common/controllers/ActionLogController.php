<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\common\ActionLog;
use backend\controllers\BaseController;

/**
 * Class ActionLogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ActionLogController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => ActionLog::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['behavior', 'method', 'url', 'remark'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->with(['backendMember', 'merchantMember', 'member']);

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
            'model' => ActionLog::findOne($id),
        ]);
    }
}