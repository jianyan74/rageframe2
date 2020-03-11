<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use common\enums\AppEnum;
use common\models\rbac\AuthRole;
use common\traits\AuthRoleTrait;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\ResultHelper;

/**
 * 默认角色
 *
 * Class AuthRoleDefaultController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthRoleDefaultController extends BaseController
{
    use AuthRoleTrait;

    /**
     * @var AuthRole
     */
    public $modelClass = AuthRole::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::MERCHANT;

    /**
     * 权限来源
     *
     * false:所有权限，true：当前角色
     *
     * @var bool
     */
    public $sourceAuthChild = false;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/modules/base/views/auth-role/';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->merchant_id = 0;
        $this->merchant_id && Yii::$app->services->merchant->setId($this->merchant_id);
    }

    /**
     * @return array|mixed
     * @throws \yii\db\Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionEdit()
    {
        /** @var AuthRole $model */
        $model = $this->findDefaultModel();
        $model->pid = 0;
        $model->app_id = $this->appId;
        $id = $model->id ?? null;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model->attributes = $data;
            $model->is_default = StatusEnum::ENABLED;

            if (!$model->save()) {
                return ResultHelper::json(422, $this->getError($model));
            }

            // 创建角色关联的权限信息
            Yii::$app->services->rbacAuthItemChild->accredit($model->id, $data['userTreeIds'] ?? [], WhetherEnum::DISABLED, $this->appId);
            Yii::$app->services->rbacAuthItemChild->accredit($model->id, $data['plugTreeIds'] ?? [], WhetherEnum::ENABLED, $this->appId);

            return ResultHelper::json(200, '提交成功');
        }

        // 获取所有权限
        $allAuth = Yii::$app->services->rbacAuthItem->findAll($this->appId);
        list($defaultFormAuth, $defaultCheckIds, $addonsFormAuth, $addonsCheckIds) = Yii::$app->services->rbacAuthRole->getJsTreeData($id, $allAuth);

        return $this->render($this->action->id, [
            'model' => $model,
            'defaultFormAuth' => $defaultFormAuth,
            'defaultCheckIds' => $defaultCheckIds,
            'addonsFormAuth' => $addonsFormAuth,
            'addonsCheckIds' => $addonsCheckIds,
            'merchant_id' => Yii::$app->services->merchant->getId()
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findDefaultModel()
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($model = $this->modelClass::findOne(['is_default' => StatusEnum::ENABLED, 'app_id' => $this->appId]))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}