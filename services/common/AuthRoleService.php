<?php

namespace services\common;

use Yii;
use common\enums\WhetherEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\common\AuthRole;
use common\components\Service;
use common\models\common\AuthItemChild;
use common\models\common\AuthAssignment;
use common\helpers\TreeHelper;

/**
 * Class AuthRoleService
 * @package services\common
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
     * 当前的角色所有权限
     *
     * @var array
     */
    protected $allAuthNames = [];

    /**
     * 获取当前用户权限的下面的所有用户id
     *
     * @param $app_id
     * @return array
     */
    public function getChildIds($app_id)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        $role = $this->getRole();
        $childRoles = $this->getChilds($app_id, $role);
        $childRoleIds = ArrayHelper::getColumn($childRoles, 'id');
        if (!$childRoleIds) {
            return [-1];
        }

        $userIds = AuthAssignment::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['in', 'role_id', $childRoleIds])
            ->select('user_id')
            ->asArray()
            ->column();

        return !empty($userIds) ? $userIds : [-1];
    }

    /**
     * 获取当前角色的子角色
     *
     * @return array
     */
    public function getChilds($app_id, array $role): array
    {
        $where = [];
        if (!empty($role)) {
            $tree = $role['tree'] . TreeHelper::prefixTreeKey($role['id']);
            $where = ['like', 'tree', $tree . '%', false];
        }

        return AuthRole::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->where(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andFilterWhere($where)
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }

    /**
     * 获取编辑的数据
     *
     * @param $id
     * @param $allAuth
     * @return array
     */
    public function getJsTreeData($id, $allAuth)
    {
        $auth = $this->getAuthById($id);

        $addonNames = [];
        $formAuth = $checkIds = $addonsFormAuth = $addonsCheckIds = [];

        // 区分默认和插件权限
        foreach ($allAuth as $item) {
            if ($item['is_addon'] == WhetherEnum::DISABLED) {
                $formAuth[] = $item;
            } else {
                if ($item['pid'] == 0) {
                    $item['pid'] = $item['addons_name'];
                }

                $addonsFormAuth[] = $item;
                $addonNames[$item['addons_name']] = $item['addons_name'];
            }
        }

        // 获取顶级插件数据
        $addons = Yii::$app->services->addons->findByNames(array_keys($addonNames));
        foreach ($addons as $addon) {
            $addonsFormAuth[] = [
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

        return [$formAuth, $checkIds, $addonsFormAuth, $addonsCheckIds];
    }

    /**
     * 基于角色获取权限信息
     *
     * @param $role
     * @param string $addons_name
     * @return array
     */
    public function getAuthByRole($role, $is_addon = WhetherEnum::DISABLED, $addons_name = '')
    {
        // 获取当前角色的权限
        $auth = AuthItemChild::find()
            ->where(['role_id' => $role['id']])
            ->andWhere(['app_id' => Yii::$app->id])
            ->andWhere(['is_addon' => $is_addon])
            ->andFilterWhere(['addons_name' => $addons_name])
            ->asArray()
            ->all();

        return array_column($auth, 'name');
    }

    /**
     * 获取用户所有的权限 - 包含插件
     *
     * @param $role
     * @return array
     */
    public function getAllAuthByRole($role)
    {
        if (!empty($this->allAuthNames)) {
            return $this->allAuthNames;
        }

        // 获取当前角色的权限
        $allAuth = AuthItemChild::find()
            ->select(['addons_name', 'name'])
            ->where(['role_id' => $role['id']])
            ->andWhere(['app_id' => Yii::$app->id])
            ->asArray()
            ->all();

        $addonsName = [];
        foreach ($allAuth as $item) {
            !isset($addonsName[$item['addons_name']]) && $this->allAuthNames[] = $item['addons_name'];

            $this->allAuthNames[] =  $item['name'];
            $addonsName[$item['addons_name']] = true;
        }

        return $this->allAuthNames;
    }

    /**
     * 获取角色名称
     *
     * @return array|mixed|string
     */
    public function getTitle()
    {
        $role = Yii::$app->services->authRole->getRole();

        return $role['title'] ?? '游客';
    }

    /**
     * 获取某角色的所有权限
     *
     * @param $id
     * @return array
     */
    public function getAuthById($id)
    {
        $auth = AuthItemChild::find()
            ->where(['role_id' => $id])
            ->with(['item'])
            ->asArray()
            ->all();

        return array_column($auth, 'item');
    }

    /**
     * 授权
     *
     * @param int $role_id 角色ID
     * @param array $data 数据
     * @param string $is_addon 是否插件
     * @param string $app_id 应用ID
     * @throws \yii\db\Exception
     */
    public function accredit($role_id, array $data, $is_addon, $app_id)
    {
        // 删除原先所有权限
        AuthItemChild::deleteAll(['role_id' => $role_id, 'is_addon' => $is_addon]);

        if (empty($data)) {
            return;
        }

        $rows = [];
        $items = Yii::$app->services->authItem->findAllByAppId($app_id, $data);

        foreach ($items as $value) {
            $rows[] = [
                $role_id,
                $value['id'],
                $value['name'],
                $value['app_id'],
                $value['is_addon'],
                $value['addons_name'],
                $value['is_menu'],
            ];
        }

        $field = ['role_id', 'item_id', 'name', 'app_id', 'is_addon', 'addons_name', 'is_menu'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItemChild::tableName(), $field, $rows)->execute();
    }

    /**
     * 获取当前角色信息
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRole()
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        if (!$this->roles) {
            /* @var $assignment \common\models\common\AuthAssignment */
            if ($assignment = Yii::$app->user->identity->assignment) {
                $assignment = ArrayHelper::toArray($assignment);
                $this->roles = AuthRole::find()
                    ->where(['id' => $assignment['role_id']])
                    ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                    ->asArray()
                    ->one();
            }
        }

        return $this->roles;
    }

    /**
     * @param $app_id
     * @return array
     */
    public function getLoginRoleChildUsers($app_id)
    {
        $role = $this->getRole();
        $childRoles = $this->getChilds($app_id, $role);
        $roles = ArrayHelper::itemsMerge($childRoles, $role['id'] ?? 0);
        $level = isset($role['level']) ? $role['level'] + 1 : 1;

        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($roles, 'id', 'title', $level), 'id', 'title');
    }

    /**
     * 获取上级角色
     *
     * 注意:如果是其他应用则需要自行获取上级
     *
     * @param $id
     * @return array
     */
    public function getDropDown($id, $app_id)
    {
        // 获取父级
        $role = $this->getRole();
        $childRoles = $this->getChilds($app_id, $role);
        !empty($role) && $childRoles = ArrayHelper::merge([$role], $childRoles);
        $childRoles = ArrayHelper::removeByValue($childRoles, $id);

        $dropDownList = ArrayHelper::itemsMerge($childRoles, $role['pid'] ?? 0);
        $dropDownList = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($dropDownList, 'id', 'title', $role['level'] ?? 1), 'id', 'title');
        Yii::$app->services->auth->isSuperAdmin() && $dropDownList = ArrayHelper::merge([0 => '顶级角色'], $dropDownList);

        return $dropDownList;
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

    /**
     * 获取当前角色的子角色
     *
     * @return array
     */
    public function findAll($app_id, $merchant_id): array
    {
        return AuthRole::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }
}