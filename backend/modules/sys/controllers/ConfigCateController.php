<?php
namespace backend\modules\sys\controllers;

use yii;
use common\helpers\ArrayHelper;
use common\models\sys\ConfigCate;
use common\components\CurdTrait;

/**
 * 配置分类控制器
 *
 * Class ConfigCateController
 * @package backend\modules\sys\controllers
 */
class ConfigCateController extends SController
{
    use CurdTrait;

    /**
     * @var
     */
    public $modelClass = 'common\models\sys\ConfigCate';

    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $models = ConfigCate::find()
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();

        return $this->render('index', [
            'models' => ArrayHelper::itemsMerge($models),
        ]);
    }

    /**
     * 编辑/新增
     *
     * @return array|mixed|string|yii\web\Response
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->level = $request->get('level', null) ?? $model->level; // 级别
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id

        if ($model->load(Yii::$app->request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax('edit', [
            'model' => $model,
            'parent_title' => $request->get('parent_title', '无'),
        ]);
    }
}
