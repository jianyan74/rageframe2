<?php

namespace common\traits;

use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\models\rbac\AuthItem;
use common\helpers\ArrayHelper;
use common\helpers\ResultHelper;

/**
 * Trait AuthItemTrait
 * @package common\traits
 * @property \yii\db\ActiveRecord|\yii\base\Model $modelClass
 * @property string $appId 应用id
 * @property string $viewPrefix 加载视图
 * @author jianyan74 <751393839@qq.com>
 */
trait AuthItemTrait
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass === null) {
            throw new InvalidConfigException('"modelClass" 属性必须设置.');
        }

        if ($this->appId === null) {
            throw new InvalidConfigException('"appId" 属性必须设置.');
        }

        if ($this->viewPrefix === null) {
            throw new InvalidConfigException('"viewPrefix" 属性必须设置.');
        }
    }

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = $this->modelClass::find()
            ->where(['app_id' => $this->appId, 'is_addon' => WhetherEnum::DISABLED])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort asc, created_at asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render($this->viewPrefix . $this->action->id, [
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
        /** @var AuthItem $model */
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id
        $model->app_id = $this->appId;
        $model->is_addon = WhetherEnum::DISABLED;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->viewPrefix . $this->action->id, [
            'model' => $model,
            'dropDownList' => Yii::$app->services->rbacAuthItem->getDropDownForEdit($this->appId, $id),
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
        if ($this->findModel($id)->delete()) {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }

    /**
     * ajax更新排序/状态
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return ResultHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return ResultHelper::json(200, '修改成功');
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