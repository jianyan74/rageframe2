<?php


namespace backend\modules\common\controllers;

use Yii;
use common\components\Curd;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\ActionBehavior;
use backend\controllers\BaseController;

/**
 * Class ActionBehaviorController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ActionBehaviorController extends BaseController
{
    use Curd;

    /**
     * @var ActionBehavior
     */
    public $modelClass = ActionBehavior::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['behavior', 'url'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'actionExplain' => ActionBehavior::$actionExplain,
            'ajaxExplain' => ActionBehavior::$ajaxExplain,
            'methodExplain' => ActionBehavior::$methodExplain,
        ]);
    }

    /**
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'actionExplain' => ActionBehavior::$actionExplain,
            'methodExplain' => ActionBehavior::$methodExplain,
            'ajaxExplain' => ActionBehavior::$ajaxExplain,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::find()->where(['id' => $id])->one()))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}