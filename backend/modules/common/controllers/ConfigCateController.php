<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\traits\Curd;
use common\enums\AppEnum;
use common\models\common\ConfigCate;
use backend\controllers\BaseController;

/**
 * Class ConfigCateController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigCateController extends BaseController
{
    use Curd;

    /**
     * @var ConfigCate
     */
    public $modelClass = ConfigCate::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->modelClass::find()
                ->where(['app_id' => AppEnum::BACKEND])
                ->orderBy('sort asc, created_at asc'),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
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
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id
        $model->app_id = AppEnum::BACKEND;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'cateDropDownList' => Yii::$app->services->configCate->getDropDownForEdit(AppEnum::BACKEND, $id),
        ]);
    }
}