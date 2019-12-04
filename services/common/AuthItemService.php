<?php

namespace services\common;

use common\enums\AuthMenuEnum;
use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\components\Service;
use common\enums\TypeEnum;
use common\enums\AppEnum;
use common\models\common\Addons;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\common\AuthItem;
use common\helpers\TreeHelper;
use common\models\common\AuthItemChild;

/**
 * Class AuthItemService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemService extends Service
{
    /**
     * @param $allAuthItem
     * @param $allMenu
     * @param $name
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function createByAddons($allAuthItem, $allMenu, $removeAppIds, $name)
    {
        // 卸载权限
        $this->uninstallAddonsByName($name);
        // 重组
        foreach ($allAuthItem as &$val) {
            $val = ArrayHelper::regroupMapToArr($val, 'name');
        }

        $defaultAuth = [
            [
                'name' => Addons::AUTH_COVER,
                'title' => '应用入口',
                'app_id' => AppEnum::BACKEND,
                'type' => TypeEnum::ADDONS,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => Addons::AUTH_RULE,
                'title' => '规则回复',
                'app_id' => AppEnum::BACKEND,
                'type' => TypeEnum::ADDONS,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => Addons::AUTH_SETTING,
                'title' => '参数设置',
                'app_id' => AppEnum::BACKEND,
                'type' => TypeEnum::ADDONS,
                'addons_name' => $name,
                'sort' => 1,
            ],
            // 商户
            [
                'name' => Addons::AUTH_COVER,
                'title' => '应用入口',
                'app_id' => AppEnum::MERCHANT,
                'type' => TypeEnum::ADDONS,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => Addons::AUTH_RULE,
                'title' => '规则回复',
                'app_id' => AppEnum::MERCHANT,
                'type' => TypeEnum::ADDONS,
                'addons_name' => $name,
                'sort' => 1,
            ],
            [
                'name' => Addons::AUTH_SETTING,
                'title' => '参数设置',
                'app_id' => AppEnum::MERCHANT,
                'type' => TypeEnum::ADDONS,
                'addons_name' => $name,
                'sort' => 1,
            ],
        ];

        // 重组路由
        $allAuth = [];
        foreach ($allAuthItem as $key => $item) {
            if (isset($allMenu[$key])) {
                $menu = ArrayHelper::regroupMapToArr($allMenu[$key]);
                $menu = ArrayHelper::getColumn(ArrayHelper::getRowsByItemsMerge($menu, 'child'), 'route');
            }

            // 菜单类型
            $is_menu = in_array($key, $removeAppIds) ? AuthMenuEnum::TOP : AuthMenuEnum::LEFT;
            $allAuth = ArrayHelper::merge($allAuth, $this->regroupByAddonsData($item, $menu, $is_menu, $name, $key));
        }

        // 创建权限
        $rows = $this->createByAddonsData(ArrayHelper::merge($defaultAuth, $allAuth));
        // 批量写入数据
        $field = ['title', 'name', 'app_id', 'type', 'addons_name', 'pid', 'level', 'is_menu', 'sort', 'tree', 'created_at', 'updated_at'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItem::tableName(), $field, $rows)->execute();

        unset($data, $allAuth, $installData, $defaultAuth);
    }

    /**
     * 卸载插件
     *
     * @param $name
     */
    public function uninstallAddonsByName($name)
    {
        AuthItem::deleteAll(['type' => TypeEnum::ADDONS, 'addons_name' => $name]);
        AuthItemChild::deleteAll(['type' => TypeEnum::ADDONS, 'addons_name' => $name]);
    }

    /**
     * 编辑下拉选择框数据
     *
     * @param $app_id
     * @param string $id
     * @return array
     */
    public function getDropDownForEdit($app_id, $id = '')
    {
        $list = AuthItem::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => $app_id, 'type' => TypeEnum::DEFAULT])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级权限'], $data);
    }

    /**
     * 获取登录用户所有权限
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAuthInLogin($app_id)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return $this->findAllByAppId($app_id);
        }

        if (!$role = Yii::$app->services->authRole->getRole()) {
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
     * @param array $ids
     * @param string $app_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllByAppId($app_id = AppEnum::BACKEND, $ids = [])
    {
        return AuthItem::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['in', 'id', $ids])
            ->select(['id', 'title', 'name', 'pid', 'level', 'app_id', 'type', 'addons_name', 'is_menu'])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();
    }

    /**
     * @param $item
     * @param $menu
     * @param $name
     * @param $app_id
     * @return array
     */
    protected function regroupByAddonsData($item, $menu, $is_menu, $name, $app_id)
    {
        foreach ($item as &$value) {
            $value['app_id'] = $app_id;
            $value['type'] = TypeEnum::ADDONS;
            $value['addons_name'] = $name;

            // 判断是否是菜单
            if (in_array($app_id, [AppEnum::BACKEND, AppEnum::MERCHANT]) && in_array($value['name'], $menu)) {
                $value['is_menu'] = $is_menu;
            }
            // 组合子级
            if (isset($value['child']) && !empty($value['child'])) {
                $value['child'] = $this->regroupByAddonsData($value['child'], $menu, $is_menu, $name, $app_id);
            }
        }

        return $item;
    }

    /**
     * @param array $data
     * @param int $pid
     * @param int $level
     * @param AuthItem $parent
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    protected function createByAddonsData(array $data, $pid = 0, $level = 1, $parent = '')
    {
        $rows = [];

        foreach ($data as $datum) {
            $model = new AuthItem();
            $model = $model->loadDefaultValues();
            $model->attributes = $datum;
            // 增加父级
            !empty($parent) && $model->setParent($parent);
            $model->pid = $pid;
            $model->level = $level;
            $model->setScenario('addonsBatchCreate');
            if (!$model->validate()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }

            // 创建子权限
            if (isset($datum['child']) && !empty($datum['child'])) {
                // 有子权限的直接写入
                if (!$model->save()) {
                    throw new UnprocessableEntityHttpException($this->getError($model));
                }

                $rows = array_merge($rows, $this->createByAddonsData($datum['child'], $model->id, $level++, $model));
            } else {
                $model->tree = !empty($parent) ?  $parent->tree . TreeHelper::prefixTreeKey($parent->id) : TreeHelper::defaultTreeKey();

                $rows[] = [
                    $model->title,
                    $model->name,
                    $model->app_id,
                    $model->type,
                    $model->addons_name,
                    $pid,
                    $level,
                    $model->is_menu ?? 0,
                    $model->sort ?? 9999,
                    $model->tree,
                    time(),
                    time(),
                ];

                unset($model);
            }
        }

        return $rows;
    }
}