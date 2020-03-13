<?php

namespace common\traits;

use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\ResultHelper;
use common\models\rbac\AuthRole;
use common\helpers\ArrayHelper;

/**
 * Trait AuthRoleTrait
 * @package common\traits
 * @property \yii\db\ActiveRecord|\yii\base\Model $modelClass
 * @property string $appId 应用id
 * @property bool $sourceAuthChild 权限来源(false:所有权限，true：当前角色)
 * @property string $viewPrefix 加载视图
 * @author jianyan74 <751393839@qq.com>
 */
trait AuthRoleTrait
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

        if ($this->sourceAuthChild === null) {
            throw new InvalidConfigException('"appId" 属性必须设置.');
        }

        if ($this->viewPrefix === null) {
            throw new InvalidConfigException('"viewPrefix" 属性必须设置.');
        }
    }

    /**
     * 首页
     *
     * @return mixed
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthRole::find()
                ->where(['app_id' => $this->appId])
                ->andWhere(['>=', 'status', StatusEnum::DISABLED])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->andFilterWhere(Yii::$app->services->rbacAuthRole->roleCondition($this->sourceAuthChild))
                ->orderBy('sort asc, created_at asc')
                ->asArray(),
            'pagination' => false
        ]);

        $role = $this->sourceAuthChild ? Yii::$app->services->rbacAuthRole->getRole() : [];

        return $this->render($this->viewPrefix . $this->action->id, [
            'dataProvider' => $dataProvider,
            'merchant_id' => $this->merchant_id,
            'role' => $role,
        ]);
    }

    /**
     * @return array|mixed
     * @throws \yii\db\Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $merchant_id = Yii::$app->services->merchant->getId();
        /** @var AuthRole $model */
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->app_id = $this->appId;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model->attributes = $data;
            $model->merchant_id = $merchant_id ?? 0;

            // 自动写入上级
            if (
                $this->sourceAuthChild &&
                !Yii::$app->services->auth->isSuperAdmin() &&
                empty($model->pid)
            ) {
                $role = Yii::$app->services->rbacAuthRole->getRole();
                $model->pid = $role['id'];
            }

            if (!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            // 创建角色关联的权限信息
            Yii::$app->services->rbacAuthItemChild->accredit($model->id, $data['userTreeIds'] ?? [], WhetherEnum::DISABLED, $this->appId);
            Yii::$app->services->rbacAuthItemChild->accredit($model->id, $data['plugTreeIds'] ?? [], WhetherEnum::ENABLED, $this->appId);

            return ResultHelper::json(200, '提交成功');
        }

        // 获取所有权限还是当前已有的权限
        if ($this->sourceAuthChild == true && !Yii::$app->services->auth->isSuperAdmin()) {
            $role = Yii::$app->services->rbacAuthRole->getRole();
            $allAuth = Yii::$app->services->rbacAuthItemChild->findItemByRoleId($role['id']);
        } else {
            $allAuth = Yii::$app->services->rbacAuthItem->findAll($this->appId);
        }

        list($defaultFormAuth, $defaultCheckIds, $addonsFormAuth, $addonsCheckIds) = Yii::$app->services->rbacAuthRole->getJsTreeData($id, $allAuth);

        return $this->render($this->viewPrefix . $this->action->id, [
            'model' => $model,
            'defaultFormAuth' => $defaultFormAuth,
            'defaultCheckIds' => $defaultCheckIds,
            'addonsFormAuth' => $addonsFormAuth,
            'addonsCheckIds' => $addonsCheckIds,
            'dropDownList' => Yii::$app->services->rbacAuthRole->getDropDownForEdit($this->appId, $id),
            'merchant_id' => $merchant_id
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