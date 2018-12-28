<?php
namespace common\services\sys;

use common\helpers\ArrayHelper;
use Yii;
use common\enums\StatusEnum;
use common\models\sys\Addons;
use common\models\sys\AuthItem;
use common\services\Service;
use common\models\sys\AuthItemChild;
use common\models\sys\AddonsAuthItemChild;

/**
 * Class Auth
 * @package common\services\sys
 */
class Auth extends Service
{
    /**
     * 当前用户权限
     *
     * @var
     */
    protected $authRole;

    /**
     * 当前基础插件权限
     *
     * @var array
     */
    protected $addonBaseAuth = [];

    /**
     * 权限校验
     *
     * @param $route
     * @param $addonName
     * @return bool
     */
    public function addonCan($route, $addonName)
    {
        $child = $this->getAddonName($addonName, $route);
        $parent = Yii::$app->user->identity->assignment->item_name ?? null;

        if (empty($parent))
        {
            return false;
        }

        if (AddonsAuthItemChild::findOne(['child' => $child, 'parent' => $parent]))
        {
            return true;
        }

        return false;
    }

    /**
     * 返回当前角色对象
     *
     * @return bool|AuthItem|null
     */
    public function getRole()
    {
        if (!($this->authRole instanceof AuthItem))
        {
            /* @var $assignment \common\models\sys\AuthAssignment */
            $assignment = Yii::$app->user->identity->assignment;
            $this->authRole = AuthItem::findOne($assignment->item_name);
        }

        return $this->authRole;
    }

    /**
     * 获取当前用户下能管辖的所有角色
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAuthRoles()
    {
        // 样式渲染辅助
        $treeStat = 1;
        // 如果不是总管理，只显示自己能管辖的角色
        if (!Yii::$app->services->sys->isAuperAdmin())
        {
            $role = Yii::$app->services->sys->auth->getRole();
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
    public function getAuthIds()
    {
        if (Yii::$app->services->sys->isAuperAdmin())
        {
            return [];
        }

        $authRole = $this->getRole();
        $position = $authRole->position . ' ' . AuthItem::POSITION_PREFIX . $authRole->key;


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

        // 做一次筛选，不然jstree会吧顶级分类下所有的子分类都选择
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
        // 获取当前编辑的权限
        $checkIds = AddonsAuthItemChild::find()
            ->where(['parent' => $name])
            ->asArray()
            ->all();

        $checkIds = array_column($checkIds, 'child');

        $formAuth = []; // 全部权限
        $tmpAuthCount = [];

        $isAuperAdmin = Yii::$app->services->sys->isAuperAdmin();

        foreach ($auths as $auth)
        {
            $tmpAuthCount[$auth['name']] = 1;

            $formAuth[] = [
                'id' => $auth['name'],
                'parent' => '#',
                'text' => $auth['title'],
                // 'icon' => 'none',
            ];

            // 设置入口权限
            if (!empty($auth->bindingCover))
            {
                $id = $this->getAddonName($auth['name'], AddonsAuthItemChild::AUTH_COVER);
                if ($isAuperAdmin || in_array($id, $this->addonBaseAuth))
                {
                    $formAuth[] = [
                        'id' => $id,
                        'parent' => $auth['name'],
                        'text' => AddonsAuthItemChild::$authExplain[AddonsAuthItemChild::AUTH_COVER],
                        // 'icon' => 'none',
                    ];

                    $tmpAuthCount[$auth['name']] += 1;
                }
            }

            // 设置规则权限
            if ($auth->is_rule == true)
            {
                $id = $this->getAddonName($auth['name'], AddonsAuthItemChild::AUTH_RULE);
                if ($isAuperAdmin || in_array($id, $this->addonBaseAuth))
                {
                    $formAuth[] = [
                        'id' => $id,
                        'parent' => $auth['name'],
                        'text' => AddonsAuthItemChild::$authExplain[AddonsAuthItemChild::AUTH_RULE],
                        // 'icon' => 'none',
                    ];

                    $tmpAuthCount[$auth['name']] += 1;
                }
            }

            // 判断设置权限
            if ($auth->is_setting == true)
            {
                $id = $this->getAddonName($auth['name'], AddonsAuthItemChild::AUTH_SETTING);
                if ($isAuperAdmin || in_array($id, $this->addonBaseAuth))
                {
                    $formAuth[] = [
                        'id' => $id,
                        'parent' => $auth['name'],
                        'text' => AddonsAuthItemChild::$authExplain[AddonsAuthItemChild::AUTH_SETTING],
                        // 'icon' => 'none',
                    ];

                    $tmpAuthCount[$auth['name']] += 1;
                }
            }

            // 设置路由权限
            foreach ($auth->authItem as $key => $item)
            {
                if ($item['route'] != AddonsAuthItemChild::AUTH_SETTING)
                {
                    $formAuth[] = [
                        'id' => $this->getAddonName($auth['name'], $item['route']),
                        'parent' => $item['addons_name'],
                        'text' => $item['description'],
                        // 'icon' => 'none',
                    ];

                    $tmpAuthCount[$auth['name']] += 1;
                }
            }
        }

        // 获取当前总权限
        $groupCheckId = AddonsAuthItemChild::find()
            ->where(['parent' => $name])
            ->select(['addons_name', ' count(*) as num'])
            ->groupBy('addons_name')
            ->asArray()
            ->all();

        $checkNum = [];
        foreach ($groupCheckId as $item)
        {
            $checkNum[$item['addons_name']] = $item['num'];
        }

        // 如果没有全选去除顶级路由
        foreach ($tmpAuthCount as $key => $value)
        {
            if (!isset($checkNum[$key]) || $checkNum[$key] != $value)
            {
                $checkIds = array_merge(array_diff($checkIds, [$key]));
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
            $role = $this->getRole();
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
        // 非总管理员
        if (!Yii::$app->services->sys->isAuperAdmin())
        {
            $addonNames = $childs = [];

            $role = $this->getRole();
            $models = AddonsAuthItemChild::find()
                ->where(['parent' => $role->name])
                ->asArray()
                ->all();

            foreach ($models as $model)
            {
                if ($model['addons_name'] == $model['child'])
                {
                    $addonNames[] = $model['child'];
                }
                else
                {
                    $childs[] = str_replace($model['addons_name'] . ':', '', $model['child']);

                    // 加入基础权限
                    $this->setAddonBaseAuth($model['child'], $model['addons_name']);
                }
            }

            $auths = Addons::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['in', 'name', $addonNames])
                ->with(['bindingCover', 'authItem' => function($query) use ($childs){
                    return $query->andWhere(['in', 'route', $childs]);
                }])
                ->all();

            return $auths;
        }

        return Addons::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->with(['bindingCover', 'authItem'])
            ->all();
    }

    /**
     * 设置基础权限
     *
     * @param $childs
     * @param $addonName
     */
    private function setAddonBaseAuth($child, $addonName)
    {
        $baseAuth = [
            $this->getAddonName($addonName, AddonsAuthItemChild::AUTH_COVER),
            $this->getAddonName($addonName, AddonsAuthItemChild::AUTH_RULE),
            $this->getAddonName($addonName, AddonsAuthItemChild::AUTH_SETTING),
        ];

        // 获取当前用户插件基础权限
        if (in_array($child, $baseAuth))
        {
            $this->addonBaseAuth[] = $child;
        }

        unset($baseAuth);
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