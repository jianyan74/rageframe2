<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\traits\Curd;
use common\models\common\AuthRole;
use common\enums\AppEnum;
use common\enums\WhetherEnum;
use common\helpers\ResultHelper;

/**
 * Class RoleController
 * @package addons\Merchants\backend\controllers
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
    public $appId = AppEnum::MERCHANT;

    public $merchant_id;

    public function init()
    {
        parent::init();

        $this->merchant_id = Yii::$app->request->get('merchant_id');
        Yii::$app->services->merchant->setId(Yii::$app->request->get('merchant_id'));
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthRole::find()
                ->where(['app_id' => $this->appId])
                ->andWhere(['>=', 'status', StatusEnum::DISABLED])
                ->andWhere(['merchant_id' => $this->merchant_id])
                ->orderBy('sort asc, created_at asc')
                ->asArray(),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'merchant_id' => $this->merchant_id
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
                return ResultHelper::json(422, $this->getError($model));
            }

            // 创建角色关联的权限信息
            Yii::$app->services->authRole->accredit($model->id, $data['userTreeIds'] ?? [], WhetherEnum::DISABLED, $this->appId);
            Yii::$app->services->authRole->accredit($model->id, $data['plugTreeIds'] ?? [], WhetherEnum::ENABLED, $this->appId);

            return ResultHelper::json(200, '提交成功');
        }

        // 所有权限信息
        $allAuth = Yii::$app->services->authItem->findAllByAppId($this->appId);
        list($defaultFormAuth, $defaultCheckIds, $addonsFormAuth, $addonsCheckIds) = Yii::$app->services->authRole->getJsTreeData($id, $allAuth);

        return $this->render($this->action->id, [
            'model' => $model,
            'defaultFormAuth' => $defaultFormAuth,
            'defaultCheckIds' => $defaultCheckIds,
            'addonsFormAuth' => $addonsFormAuth,
            'addonsCheckIds' => $addonsCheckIds,
            'dropDownList' => $this->getDropDown($id),
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
    protected function getDropDown($id)
    {
        $role = Yii::$app->services->authRole->getRole();
        $childRoles = Yii::$app->services->authRole->getChilds($this->appId, $role);
        !empty($role) && $childRoles = ArrayHelper::merge([$role], $childRoles);
        $childRoles = ArrayHelper::removeByValue($childRoles, $id);

        $dropDownList = ArrayHelper::itemsMerge($childRoles, $role['pid'] ?? 0);
        $dropDownList = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($dropDownList), 'id', 'title');
        $dropDownList = ArrayHelper::merge([0 => '顶级角色'], $dropDownList);

        return $dropDownList;
    }
}