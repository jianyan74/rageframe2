<?php
namespace common\services\sys;

use Yii;
use common\models\sys\Addons;
use common\models\sys\AuthItem;
use common\services\Service;
use common\models\sys\AuthItemChild;
use common\helpers\ArrayHelper;
use common\models\sys\AddonsAuthItem;
use common\models\sys\AddonsAuthItemChild;

/**
 * Class Auth
 * @package common\services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class Auth extends Service
{
    /**
     * 当前用户权限
     *
     * @var \common\models\sys\AuthItem
     */
    protected $role;

    /**
     * 当前基础插件权限
     *
     * @var array
     */
    protected $addonBaseAuth = [];

    /**
     * 拥有的插件权限
     *
     * @var array
     */
    protected $addonAuth = [];

    /**
     * 当前系统权限
     *
     * @var array
     */
    protected $sysAuth = [];

    /**
     * 获取当前的角色
     *
     * @return AuthItem|null
     */
    public function getRole()
    {
        if (!$this->role)
        {
            /* @var $assignment \common\models\sys\AuthAssignment */
            if ($assignment = Yii::$app->user->identity->assignment)
            {
                $this->role = AuthItem::find()
                    ->where(['name' => $assignment->item_name, 'type' => AuthItem::ROLE])
                    ->one();
            }
        }

        return $this->role;
    }

    /**
     * 获取当前用户下能管辖的所有角色
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChildRoles()
    {
        // 样式渲染辅助
        $treeStat = 1;
        // 如果不是总管理，只显示自己能管辖的角色
        if (!Yii::$app->services->sys->isAuperAdmin())
        {
            $role = $this->getRole();
            $parent_key = $role->key;
            $treeStat += $role->level;
            $models = AuthItem::getChilds($role);
        }
        else
        {
            $models = AuthItem::find()
                ->where(['type' => AuthItem::ROLE])
                ->orderBy('sort asc')
                ->asArray()
                ->all();

            $parent_key = 0;
        }

        return [$models, $parent_key, $treeStat];
    }

    /**
     * 获取当前用户权限的下面的所有用户id
     *
     * @return array
     */
    public function getChildRoleIds()
    {
        if (Yii::$app->services->sys->isAuperAdmin())
        {
            return [];
        }

        $role = $this->getRole();
        $position = $role->position . ' ' . AuthItem::POSITION_PREFIX . $role->key;
        $models = AuthItem::getMultiDate(['like', 'position', $position . '%', false], ['*'], 'level asc', ['authAssignments']);

        $ids = [];
        foreach ($models as $model)
        {
            foreach ($model['authAssignments'] as $authAssignments)
            {
                $ids[] = $authAssignments['user_id'];
            }
        }

        return !empty($ids) ? $ids : [-1];
    }

    /**
     * 获取用户权限jsTree数据
     *
     * @param string $name 角色名称
     * @return array
     */
    public function getAuthJsTreeData($name)
    {
        // 获取当前登录的所有权限
        $auths = $this->getUserAuth();
        // 获取当前角色权限
        $authItemChild = AuthItemChild::find()
            ->where(['parent' => $name])
            ->with(['child0'])
            ->asArray()
            ->all();

        $checkIds = [];
        $tmpChildCount = [];
        foreach ($authItemChild as $value)
        {
            $checkIds[] = $value['child0']['key'];

            // 统计他自己出现次数
            if ($value['child0']['parent_key'] > 0)
            {
                !isset($tmpChildCount[$value['child0']['parent_key']])
                    ? $tmpChildCount[$value['child0']['parent_key']] = 1
                    : $tmpChildCount[$value['child0']['parent_key']] += 1;
            }
        }

        $formAuth = []; // 全部权限
        $tmpAuthCount = [];
        foreach ($auths as $auth)
        {
            $formAuth[] = [
                'id' => $auth['key'],
                'parent' => !empty($auth['parent_key']) ? $auth['parent_key'] : '#',
                'text' => $auth['description'],
                // 'icon' => 'none'
            ];

            $count = count(ArrayHelper::getChildIds($auths, $auth['key'], 'key', 'parent_key'));
            $tmpAuthCount[$auth['key']] = $count == 0 ? 1 : $count;
        }

        // 做一次筛选，不然jstree会把顶级分类下所有的子分类都选择
        foreach ($tmpChildCount as $key => $item)
        {
            if (isset($tmpAuthCount[$key]) && $item != $tmpAuthCount[$key])
            {
                $checkIds = array_merge(array_diff($checkIds, [$key]));
            }
        }

        unset($tmpChildCount, $tmpChildCount, $auths);

        return [$formAuth, $checkIds];
    }

    /**
     * 获取插件jsTree数据
     *
     * @param string $name 角色名称
     * @return array
     */
    public function getAddonsAuthJsTreeData($name)
    {
        // 获取当前登录的所有权限
        $auths = $this->getAddonAuth();

        $addonNames = $formAuth = $authCount = [];
        foreach ($auths as $auth)
        {
            $addonNames[$auth['addons_name']] = $auth['addons_name'];

            $formAuth[] = [
                'id' => $this->getAddonName($auth['addons_name'], $auth['route']),
                'parent' => $auth['addons_name'],
                'text' => $auth['description'],
                // 'icon' => 'none',
            ];

            !isset($authCount[$auth['addons_name']]) && $authCount[$auth['addons_name']] = 1;
            $authCount[$auth['addons_name']] += 1;
        }

        // 父级
        $addons = Addons::find()
            ->select(['title', 'name'])
            ->where(['in', 'name', $addonNames])
            ->asArray()
            ->all();
        foreach ($addons as $addon)
        {
            $formAuth[] = [
                'id' => $addon['name'],
                'parent' => '#',
                'text' => $addon['title'],
                // 'icon' => 'none',
            ];
        }

        // 获取当前编辑的选中权限
        $itemChild = AddonsAuthItemChild::find()
            ->where(['parent' => $name])
            ->asArray()
            ->all();
        $checkIds = array_column($itemChild, 'child');

        // 循环去掉选中
        foreach ($itemChild as $item)
        {
            isset($authCount[$item['addons_name']]) && $authCount[$item['addons_name']] -= 1;
        }

        // 去除重复
        foreach ($checkIds as $k => $checkId)
        {
            if (isset($authCount[$checkId]) && $authCount[$checkId] != 0)
            {
                $checkIds = array_merge(array_diff($checkIds, [$checkId]));
            }
        }

        return [$formAuth, $checkIds];
    }

    /**
     * 获取当前自己拥有的所有权限
     *
     * @param $name
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getUserAuth()
    {
        if (!Yii::$app->services->sys->isAuperAdmin())
        {
            if (!($role = $this->getRole()))
            {
                return [];
            }

            $models = AuthItemChild::find()
                ->where(['parent' => $role->name])
                ->asArray()
                ->all();

            $childs = array_column($models, 'child');

            return AuthItem::find()
                ->where(['type' => AuthItem::AUTH])
                ->andWhere(['in', 'name', $childs])
                ->orderBy('sort asc')
                ->asArray()
                ->all();
        }

        return AuthItem::find()
            ->where(['type' => AuthItem::AUTH])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }

    /**
     * 获取当前自己拥有的所有插件权限
     *
     * @param $name
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAddonAuth()
    {
        if (!empty($this->addonAuth))
        {
            return $this->addonAuth;
        }

        $authItems = AddonsAuthItem::find()
            ->orderBy('type asc')
            ->asArray()
            ->all();

        // 非总管理员
        if (!Yii::$app->services->sys->isAuperAdmin())
        {
            if (!($role = $this->getRole()))
            {
                return [];
            }

            $models = AddonsAuthItemChild::find()
                ->where(['parent' => $role->name])
                ->asArray()
                ->all();

            $childAuth = [];
            foreach ($models as $model)
            {
                $key = $model['child'];
                $childAuth[$key] = $model;
            }

            foreach ($authItems as $k => $item)
            {
                $key = $this->getAddonName($item['addons_name'], $item['route']);

                if (!isset($childAuth[$key]))
                {
                    unset($authItems[$k]);
                }
            }
        }

        $this->addonAuth = $authItems;
        return $authItems;
    }

    /**
     * 设置扩展模块权限前缀
     *
     * @param $name
     * @param $child
     * @return string
     */
    private function getAddonName($name, $child)
    {
        return $name . ':' . $child;
    }
}