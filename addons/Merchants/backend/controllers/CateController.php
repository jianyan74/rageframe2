<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\traits\Curd;
use common\models\merchant\Cate;

/**
 * 商户分类
 *
 * Class CateController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CateController extends BaseController
{
    use Curd;

    /**
     * @var Cate
     */
    public $modelClass = Cate::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Cate::find()
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
     * @return mixed|string|\yii\console\Response|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id

        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'dropDown' => Yii::$app->services->merchantCate->getDropDownForEdit($id),
        ]);
    }
}