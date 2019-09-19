<?php

namespace addons\RfMerchants\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\helpers\ArrayHelper;
use common\components\Curd;
use common\models\common\AuthRole;
use common\enums\AppEnum;
use common\enums\AuthTypeEnum;
use common\helpers\ResultDataHelper;

/**
 * Class RoleController
 * @package addons\RfMerchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RoleController extends BaseController
{
    use Curd;

    /**
     * @var AuthRole
     */
    public $modelClass = AuthRole::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::BACKEND;

    public $merchant_id;

    public function init()
    {
        parent::init();

        $this->merchant_id = Yii::$app->request->get('merchant_id');
        Yii::$app->services->merchant->setId($this->merchant_id);
    }

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->services->authRole->getRole();
        $childRoles = Yii::$app->services->authRole->getChildList($this->appId, $role);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false
        ]);

        $dataProvider->setModels($childRoles);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'role' => $role,
            'merchant_id' => $this->merchant_id,
        ]);
    }

    /**
     * @return array|string
     * @throws Yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->app_id = $this->appId;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model->attributes = $data;

            if (!$model->save()) {
                return ResultDataHelper::json(422, $this->getError($model));
            }

            // 创建角色关联的权限信息
            Yii::$app->services->authRole->accredit($model->id, $data['userTreeIds'] ?? [], AuthTypeEnum::TYPE_DEFAULT, $this->appId);
            Yii::$app->services->authRole->accredit($model->id, $data['plugTreeIds'] ?? [], AuthTypeEnum::TYPE_ADDONS, $this->appId);

            return ResultDataHelper::json(200, '提交成功');
        }

        // 获取当前角色权限
        list($defaultFormAuth, $defaultCheckIds, $addonsFormAuth, $addonsCheckIds) = Yii::$app->services->authRole->getJsTreeData($id, $this->appId);

        return $this->render($this->action->id, [
            'model' => $model,
            'defaultFormAuth' => $defaultFormAuth,
            'defaultCheckIds' => $defaultCheckIds,
            'addonsFormAuth' => $addonsFormAuth,
            'addonsCheckIds' => $addonsCheckIds,
            'dropDownList' => $this->getDropDownList($id),
            'merchant_id' => $this->merchant_id
        ]);
    }

    /**
     * 获取上级角色
     *
     * 注意:如果是其他应用则需要自行获取上级
     *
     * @param $id
     * @return array
     */
    protected function getDropDownList($id)
    {
        // 获取父级
        $role = Yii::$app->services->authRole->getRole();
        $childRoles = Yii::$app->services->authRole->getChildList($this->appId, $role);
        !empty($role) && $childRoles = ArrayHelper::merge([$role], $childRoles);
        foreach ($childRoles as $k => $childRole) {
            if ($childRole['id'] == $id) {
                unset($childRoles[$k]);
            }
        }

        $dropDownList = ArrayHelper::itemsMerge($childRoles, $role['pid'] ?? 0);
        $dropDownList = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($dropDownList, 'id', 'title', $role['level'] ?? 1), 'id', 'title');
        Yii::$app->services->auth->isSuperAdmin() && $dropDownList = ArrayHelper::merge([0 => '顶级角色'], $dropDownList);

        return $dropDownList;
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
            return $this->message("删除成功", $this->redirect(['index', 'merchant_id' => $this->merchant_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'merchant_id' => $this->merchant_id]), 'error');
    }
}