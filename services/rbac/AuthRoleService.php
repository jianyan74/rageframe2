<?php

namespace services\rbac;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\ArrayHelper;
use common\helpers\TreeHelper;
use common\models\rbac\AuthRole;
use common\enums\AppEnum;
use yii\web\UnauthorizedHttpException;

/**
 * 角色
 *
 * Class AuthRoleService
 * @package services\rbac
 * @author jianyan74 <751393839@qq.com>
 */
class AuthRoleService extends Service
{
    /**
     * 角色信息
     *
     * @var array
     */
    protected $roles = [];

    /**
     * 获取当前角色信息
     *
     * @return array|\yii\db\ActiveRecord|null
     * @throws UnauthorizedHttpException
     */
    public function getRole()
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        if (!$this->roles) {
            /* @var $assignment \common\models\rbac\AuthAssignment */
            if (!($assignment = Yii::$app->user->identity->assignment)) {
                throw new UnauthorizedHttpException('未授权角色，请联系管理员');
            }

            $merchant_id = $this->getMerchantId();
            if (Yii::$app->id == AppEnum::BACKEND) {
                $merchant_id = '';
            }

            $assignment = ArrayHelper::toArray($assignment);
            $this->roles = AuthRole::find()
                ->where(['id' => $assignment['role_id']])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $merchant_id])
                ->asArray()
                ->one();

            if (!$this->roles) {
                throw new UnauthorizedHttpException('授权的角色已失效，请联系管理员');
            }
        }

        return $this->roles;
    }

    /**
     * 获取编辑的数据
     *
     * @param int $role_id
     * @param array $allAuth
     * @return array
     *
     */
    public function getJsTreeData($role_id, array $allAuth)
    {
        // 当前角色已有的权限
        $auth = Yii::$app->services->rbacAuthItemChild->findItemByRoleId($role_id);

        $addonName = $formAuth = $checkIds = $addonFormAuth = $addonsCheckIds = [];

        // 区分默认和插件权限
        foreach ($allAuth as $item) {
            if ($item['is_addon'] == WhetherEnum::DISABLED) {
                $formAuth[] = $item;
            } else {
                if ($item['pid'] == 0) {
                    $item['pid'] = $item['addons_name'];
                }

                $addonFormAuth[] = $item;
                $addonName[$item['addons_name']] = $item['addons_name'];
            }
        }

        // 获取顶级插件数据
        $addons = Yii::$app->services->addons->findByNames(array_keys($addonName));
        foreach ($addons as $addon) {
            $addonFormAuth[] = [
                'id' => $addon['name'],
                'pid' => 0,
                'title' => $addon['title'],
            ];
        }

        // 区分默认和插件权限ID
        foreach ($auth as $value) {
            if ($value['is_addon'] == WhetherEnum::DISABLED) {
                $checkIds[] = $value['id'];
            } else {
                $addonsCheckIds[] = $value['id'];
            }
        }

        return [$formAuth, $checkIds, $addonFormAuth, $addonsCheckIds];
    }

    /**
     * 获取角色名称
     *
     * @return array|mixed|string
     * @throws UnauthorizedHttpException
     */
    public function getTitle()
    {
        return $this->getRole()['title'] ?? '游客';
    }

    /**
     * 获取上级角色
     *
     * 注意:如果是其他应用则需要自行获取上级
     *
     * @param $id
     * @return array
     */
    public function getDropDownForEdit($app_id, $id = '')
    {
        $list = $this->findAll($app_id, Yii::$app->services->merchant->getId());
        $list = ArrayHelper::removeByValue($list, $id);

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        if (Yii::$app->services->auth->isSuperAdmin()) {
            return ArrayHelper::merge([0 => '顶级角色'], $data);
        }

        return $data;
    }

    /**
     * 获取是否所有角色的条件
     *
     * @param bool $sourceAuthChild
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function roleCondition($sourceAuthChild = false)
    {
        if ($sourceAuthChild == false) {
            return [];
        }

        $role = Yii::$app->services->rbacAuthRole->getRole();
        if (empty($role)) {
            return [];
        }

        $tree = $role['tree'] . TreeHelper::prefixTreeKey($role['id']);

        return ['like', 'tree', $tree . '%', false];
    }

    /**
     * 获取分配角色列表
     *
     * @param string $app_id 应用id
     * @param bool $sourceAuthChild 权限来源(false:所有权限，true：当前角色)
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function getDropDown($app_id, $sourceAuthChild = false)
    {
        $list = $this->findAll($app_id, Yii::$app->services->merchant->getId(), $this->roleCondition($sourceAuthChild));

        $pid = 0;
        $treeStat = 1;
        if ($sourceAuthChild == true && ($role = Yii::$app->services->rbacAuthRole->getRole())) {
            $pid = $role['id'];
            $treeStat = $role['level'] + 1;
        }

        $models = ArrayHelper::itemsMerge($list, $pid);

        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models, 'id', 'title', $treeStat), 'id', 'title');
    }

    /**
     * 获取当前角色的子角色
     *
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function getChildes($app_id): array
    {
      return $this->findAll($app_id, Yii::$app->services->merchant->getId(), $this->roleCondition(true));
    }

    /**
     * 复制默认角色进入商户
     *
     * @param $app_id
     * @param $merchant_id
     * @return bool|AuthRole
     * @throws \yii\db\Exception
     */
    public function cloneInDefault($app_id, $merchant_id)
    {
        if (!($default = $this->findDefault($app_id))) {
            return false;
        }

        $role = new AuthRole();
        $role->attributes = $default;
        $role->merchant_id = $merchant_id;
        if ($role->save()) {
            Yii::$app->services->rbacAuthItemChild->accreditByDefault($role, $default['authItemChild']);
            return $role;
        }

        return false;
    }

    /**
     * 查询所有角色信息
     *
     * @return array
     */
    public function findAll($app_id, $merchant_id, $condition = []): array
    {
        return AuthRole::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->andFilterWhere($condition)
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }

    /**
     * 获取默认已启用的角色
     *
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findDefault($app_id)
    {
        return AuthRole::find()
            ->where([
                'is_default' => StatusEnum::ENABLED,
                'app_id' => $app_id,
                'status' => StatusEnum::ENABLED,
            ])
            ->with('authItemChild')
            ->asArray()
            ->one();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findById($id)
    {
        return AuthRole::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
    }
}