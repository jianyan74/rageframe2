<?php
namespace addons\RfExample\backend\controllers;

use Yii;
use common\components\Curd;
use common\helpers\ArrayHelper;
use common\controllers\AddonsBaseController;
use addons\RfExample\common\models\Cate;

/**
 * 无限级分类
 *
 * Class CateController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CateController extends AddonsBaseController
{
    use Curd;

    /**
     * @var string
     */
    public $modelClass = 'addons\RfExample\common\models\Cate';

    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $models = Cate::find()
            ->orderBy('sort Asc,created_at Asc')
            ->asArray()
            ->all();

        return $this->render('index', [
            'models' => ArrayHelper::itemsMerge($models),
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return array|mixed|string|yii\web\Response
     */
    public function actionAjaxEdit()
    {
        $request  = Yii::$app->request;
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

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'parent_title' => $request->get('parent_title', '无'),
        ]);
    }
}