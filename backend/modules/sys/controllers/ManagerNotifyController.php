<?php
namespace backend\modules\sys\controllers;

use common\models\sys\NotifyManager;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\common\SearchModel;
use common\models\sys\Notify;
use common\enums\StatusEnum;
use backend\modules\sys\models\NotifyMessageForm;

/**
 * 消息管理
 *
 * Class ManagerNotifyController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerNotifyController extends SController
{
    /**
     * 公告
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAnnounce()
    {
        $searchModel = new SearchModel([
            'model' => NotifyManager::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['manager_id' => Yii::$app->user->id])
            ->andWhere(['type' => Notify::TYPE_ANNOUNCE])
            ->with(['notifySenderForManager']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 公告详情
     *
     * @param $id
     * @return mixed|string
     */
    public function actionAnnounceView($id)
    {
        if (empty($id) || empty(($model = NotifyManager::find()->where(['id' => $id, 'status' => StatusEnum::ENABLED])->one())))
        {
            return $this->message('找不到该公告', $this->redirect(['index']), 'error');
        }

        // 设置公告为已读
        Yii::$app->services->sys->notify->read(Yii::$app->user->id, [$model->notify_id]);

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 私信
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionMessage()
    {
        $searchModel = new SearchModel([
            'model' => NotifyManager::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['content'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->with(['notifySenderForManager'])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_MESSAGE, 'manager_id' => Yii::$app->user->id]);

        if ($data = $dataProvider->getModels())
        {
            $ids = [];
            foreach ($data as $datum)
            {
                $ids[] = $datum->notify_id;
            }

            // 设置私信为已读
            Yii::$app->services->sys->notify->read(Yii::$app->user->id, $ids);
        }

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}