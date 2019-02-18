<?php
namespace backend\modules\sys\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\components\CurdTrait;
use common\models\common\SearchModel;
use common\models\sys\Notify;
use backend\modules\sys\models\NotifyMessageForm;

/**
 * 私信回复
 *
 * Class NotifyMessageController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyMessageController extends SController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = "common\models\sys\Notify";

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Notify::class,
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
            ->with('meassageManager')
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_MESSAGE, 'sender_id' => Yii::$app->user->id]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * ajax编辑/创建
     *
     * @return array|mixed|string|Response
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $model = new NotifyMessageForm();
        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            return Yii::$app->services->sys->notify->createMessage($model->content, Yii::$app->user->id, $model->toManagerId)
                ? $this->redirect(['index'])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}