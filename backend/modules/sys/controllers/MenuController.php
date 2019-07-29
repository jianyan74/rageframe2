<?php
namespace backend\modules\sys\controllers;

use Yii;
use common\components\Curd;
use common\models\sys\Menu;
use common\helpers\ArrayHelper;
use backend\controllers\BaseController;
use yii\data\ActiveDataProvider;

/**
 * Class MenuController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Menu::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $cate_id = Yii::$app->request->get('cate_id', Yii::$app->services->sysMenuCate->getFirstId());

        $query = $this->modelClass::find()
            ->orderBy('sort asc,created_at asc')
            ->filterWhere(['cate_id' => $cate_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'cates' => Yii::$app->services->sysMenuCate->getDefaultList(),
            'cate_id' => $cate_id,
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
        $model->cate_id = $request->get('cate_id', null) ?? $model->cate_id; // 分类id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index', 'cate_id' => $model->cate_id])
                : $this->message($this->getError($model), $this->redirect(['index', 'cate_id' => $model->cate_id]), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'cates' => Yii::$app->services->sysMenuCate->getMapDefaultMapList(),
            'menuDropDownList' => Yii::$app->services->sysMenu->getDropDownList($id),
        ]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if (($model = $this->findModel($id))->delete()) {
            return $this->message("删除成功", $this->redirect(['index', 'cate_id' => $model->cate_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'cate_id' => $model->cate_id]), 'error');
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
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}