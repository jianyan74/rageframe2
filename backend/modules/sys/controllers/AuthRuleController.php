<?php
namespace backend\modules\sys\controllers;

use Yii;
use yii\web\Response;
use yii\data\Pagination;
use yii\widgets\ActiveForm;
use common\models\sys\AuthRule;
use common\components\CurdTrait;

/**
 * RBAC规则控制器
 *
 * Class AuthRuleController
 * @package backend\modules\sys\controllers
 */
class AuthRuleController extends SController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\sys\AuthRule';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = AuthRule::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('created_at desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 编辑/新增
     *
     * @return array|mixed|string|Response
     */
    public function actionAjaxEdit()
    {
        $request  = Yii::$app->request;
        $name = $request->get('name');
        $model = $this->findModel($name);
        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['index']), 'error');
        }

        $model->className = AuthRule::getClassName($model->data);

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 删除
     *
     * @param $name
     * @return mixed
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($name)
    {
        if ($this->findModel($name)->delete())
        {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }
}