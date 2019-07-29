<?php

namespace services\common;

use Yii;
use common\enums\AuthEnum;
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
     * 获取当前用户权限的下面的所有用户id
     *
     * @return array
     */
    public function getChildIds($type = AuthEnum::TYPE_BACKEND)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        $role = $this->getRole();
        $childRoles = $this->getChildList($type, $role);
        $childRoleIds = ArrayHelper::getColumn($childRoles, 'id');
        if (!$childRoleIds) {
            return [-1];
        }

        $userIds = AuthAssignment::find()
            ->where(['type' => $type])
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
    public function getChildList($type, array $role): array
    {
        $where = [];
        if (!empty($role)) {
            $tree = $role['tree'] . TreeHelper::prefixTreeKey($role['id']);
            $where = ['like', 'tree', $tree . '%', false];
        }

        return AuthRole::find()
            ->where(['type' => $type])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere($where)
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }

    /**
     * @param $id
     * @return array
     */
    public function getJsTreeData($id)
    {
        $allAuth = $this->getAuth();
        $auth = $this->getAuthById($id);
        $addonNames = $formAuth = $checkIds = $addonsFormAuth = $addonsCheckIds = [];

        // 已选中的权限id
        $tmpChildCount = [];
        foreach ($auth as $value) {
            if ($value['type_child'] == AuthEnum::TYPE_CHILD_DEFAULT) {
                $checkIds[] = $value['id'];
            } else {
                $addonsCheckIds[] = $value['id'];
            }

            // 统计他自己出现次数
            if ($value['pid'] > 0) {
                !isset($tmpChildCount[$value['pid']]) ? $tmpChildCount[$value['pid']] = 1 : $tmpChildCount[$value['pid']] += 1;
            }
        }

        // 所有权限数据
        $tmpAuthCount = [];
        foreach ($allAuth as $item) {
            $data = [
                'id' => $item['id'],
                'parent' => !empty($item['pid']) ? $item['pid'] : '#',
                'text' => $item['title'],
                // 'icon' => 'none'
            ];

            if ($item['type_child'] == AuthEnum::TYPE_CHILD_DEFAULT) {
                $formAuth[] = $data;

                $count = count(ArrayHelper::getChildIds($allAuth, $item['id']));
                $tmpAuthCount[$item['id']] = $count == 0 ? 1 : $count;
            } else {
                $addonNames[$item['addons_name']] = $item['addons_name'];
                $data['parent'] = $item['addons_name'];
                $addonsFormAuth[] = $data;
            }

            unset($data);
        }

        // 获取顶级插件数据
        $addons = Yii::$app->services->addons->findByNames(array_keys($addonNames));
        foreach ($addons as $addon) {
            $addonsFormAuth[] = [
                'id' => $addon['name'],
                'parent' => '#',
                'text' => $addon['title'],
                // 'icon' => 'none'
            ];
        }

        // 做一次筛选，不然jstree会把顶级分类下所有的子分类都选择
        foreach ($tmpChildCount as $key => $item) {
            if (isset($tmpAuthCount[$key]) && $item != $tmpAuthCount[$key]) {
                $checkIds = array_merge(array_diff($checkIds, [$key]));
            }
        }

        unset($tmpChildCount, $tmpAuthCount, $auth, $allAuth);
        return [$formAuth, $checkIds, $addonsFormAuth, $addonsCheckIds];
    }

    /**
     * 获取登录用户所有权限
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAuth()
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return Yii::$app->services->authItem->getList();
        }

        if (!$role = $this->getRole()) {
            return [];
        }

        // 获取当前角色的权限
        $auth = AuthItemChild::find()
            ->where(['in', 'role_id', $role['id']])
            ->with(['item'])
            ->asArray()
            ->all();

        return array_column($auth, 'item');
    }

    /**
     * 基于角色获取权限信息
     *
     * @param $role
     * @param string $addons_name
     * @return array
     */
    public function getAuthByRole($role, $type_child = AuthEnum::TYPE_CHILD_DEFAULT, $addons_name = '')
    {
        // 获取当前角色的权限
        $auth = AuthItemChild::find()
            ->where(['role_id' => $role['id']])
            ->andWhere(['type' => Yii::$app->id])
            ->andWhere(['type_child' => $type_child])
            ->andFilterWhere(['addons_name' => $addons_name])
            ->asArray()
            ->all();

        return array_column($auth, 'name');
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
     * 获取权限
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
     * @param $role_id
     * @param array $items
     * @throws \yii\db\Exception
     */
    public function accredit($role_id, array $data, $type_child)
    {
        // 删除原先所有权限
        AuthItemChild::deleteAll(['role_id' => $role_id, 'type_child' => $type_child]);

        if (empty($data)) {
            return true;
        }

        $rows = [];
        $items = Yii::$app->services->authItem->getList($data);
        foreach ($items as $value) {
            $rows[] = [
                $role_id,
                $value['id'],
                $value['name'],
                $value['type'],
                $value['type_child'],
                $value['addons_name'],
                $value['is_menu']
            ];
        }

        $field = ['role_id', 'item_id', 'name', 'type', 'type_child', 'addons_name', 'is_menu'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItemChild::tableName(), $field,
            $rows)->execute();
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
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getRoleById($id)
    {
        return AuthRole::find()
            ->where(['id' => $id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->one();
    }
}