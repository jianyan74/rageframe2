<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\components\Curd;
use common\enums\StatusEnum;
use common\enums\AppEnum;
use common\enums\TypeEnum;
use common\models\common\AuthItem;
use backend\controllers\BaseController;

/**
 * Class AuthItemController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemController extends BaseController
{
    use Curd;

    /**
     * @var AuthItem
     */
    public $modelClass = AuthItem::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = $this->modelClass::find()
            ->where(['app_id' => AppEnum::BACKEND, 'type' => TypeEnum::DEFAULT])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort asc, created_at asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id', '');
        /** @var AuthItem $model */
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->app_id = AppEnum::BACKEND;
        $model->type = TypeEnum::DEFAULT;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'dropDownList' => Yii::$app->services->authItem->getDropDownForEdit(AppEnum::BACKEND, $id),
        ]);
    }
}