<?php

namespace services\common;

use Yii;
use common\enums\AppEnum;
use common\enums\AuthTypeEnum;
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
    public function getChildIds($app_id = AppEnum::BACKEND)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        $role = $this->getRole();
        $childRoles = $this->getChildList($app_id, $role);
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
    public function getChildList($app_id, array $role): array
    {
        $where = [];
        if (!empty($role)) {
            $tree = $role['tree'] . TreeHelper::prefixTreeKey($role['id']);
            $where = ['like', 'tree', $tree . '%', false];
        }

        return AuthRole::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere($where)
            ->orderBy('sort asc, created_at asc')
            ->asArray()
            ->all();
    }

    /**
     * @param int $id
     * @param string $app_id 应用id
     * @return array
     */
    public function getJsTreeData($id, $app_id)
    {
        $allAuth = $this->getAuth($app_id);
        $auth = $this->getAuthById($id);

        $addonNames = [];
        $formAuth = $checkIds = $addonsFormAuth = $addonsCheckIds = [];

        // 区分默认和插件权限
        foreach ($allAuth as $item) {
            if ($item['type'] == AuthTypeEnum::TYPE_DEFAULT) {
                $formAuth[] = $item;
            } else {
                $item['pid'] = $item['addons_name'];
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
            if ($value['type'] == AuthTypeEnum::TYPE_DEFAULT) {
                $checkIds[] = $value['id'];
            } else {
                $addonsCheckIds[] = $value['id'];
            }
        }

        return [$formAuth, $checkIds, $addonsFormAuth, $addonsCheckIds];
    }

    /**
     * 获取登录用户所有权限
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAuth($app_id)
    {
        if ($app_id != AppEnum::BACKEND) {
            return Yii::$app->services->authItem->getList($app_id);
        }

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
    public function getAuthByRole($role, $type = AuthTypeEnum::TYPE_DEFAULT, $addons_name = '')
    {
        // 获取当前角色的权限
        $auth = AuthItemChild::find()
            ->where(['role_id' => $role['id']])
            ->andWhere(['app_id' => Yii::$app->id])
            ->andWhere(['type' => $type])
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
     * 授权
     *
     * @param int $role_id 角色ID
     * @param array $data 数据
     * @param string $type 类型
     * @param string $app_id 应用ID
     * @return bool
     * @throws \yii\db\Exception
     */
    public function accredit($role_id, array $data, $type, $app_id)
    {
        // 删除原先所有权限
        AuthItemChild::deleteAll(['role_id' => $role_id, 'type' => $type]);

        if (empty($data)) {
            return true;
        }

        $rows = [];
        $items = Yii::$app->services->authItem->getList($app_id, $data);

        foreach ($items as $value) {
            $rows[] = [
                $role_id,
                $value['id'],
                $value['name'],
                $value['app_id'],
                $value['type'],
                $value['addons_name'],
                $value['is_menu']
            ];
        }

        $field = ['role_id', 'item_id', 'name', 'app_id', 'type', 'addons_name', 'is_menu'];
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