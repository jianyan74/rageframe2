<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\enums\AppEnum;
use common\traits\Curd;
use common\models\common\Menu;

/**
 * Class MenuController
 * @package addons\Merchants\backend\controllers
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
     * @var string
     */
    public $app_id = AppEnum::MERCHANT;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $cate_id = Yii::$app->request->get('cate_id', Yii::$app->services->menuCate->findFirstId($this->app_id));

        $query = $this->modelClass::find()
            ->orderBy('sort asc,created_at asc')
            ->filterWhere(['cate_id' => $cate_id])
            ->andWhere(['app_id' => $this->app_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render($this->viewPrefix . 'menu/index', [
            'dataProvider' => $dataProvider,
            'cates' => Yii::$app->services->menuCate->findDefault($this->app_id),
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

        if ($model->isNewRecord && $model->parent) {
            $model->cate_id = $model->parent->cate_id;
        }

        $menuCate = Yii::$app->services->menuCate->findById($model->cate_id);

        return $this->renderAjax($this->viewPrefix . 'menu/ajax-edit', [
            'model' => $model,
            'cates' => Yii::$app->services->menuCate->getDefaultMap($this->app_id),
            'menuDropDownList' => Yii::$app->services->menu->getDropDown($menuCate, AppEnum::MERCHANT, $id),
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
}