<?php
namespace services\common;

use common\components\Service;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\ArrayHelper;
use common\models\common\AuthGroup;
use common\models\common\AuthGroupItem;
use common\models\common\AuthItemChild;
use Yii;

class AuthGroupService extends Service
{
    protected $group = [];

    protected $allAuthNames = [];

    /**
     * 获取用户所有的权限 - 包含插件
     *
     * @param $group
     * @return array
     */
    public function getAllAuthByGroup($group)
    {
        if (!empty($this->allAuthNames)) {
            return $this->allAuthNames;
        }

        // 获取当前角色的权限
        $allAuth = AuthGroupItem::find()
            ->select(['addons_name', 'name'])
            ->where(['group_id' => $group['id']])
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
     * 获取编辑的数据
     *
     * @param $id
     * @param $allAuth
     * @return array
     */
    public function getJsTreeData($id, $allAuth)
    {
        $auth = $this->getAuthByGroupId($id);

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
    public function getAuthByGroup($group, $is_addon = WhetherEnum::DISABLED, $addons_name = '')
    {
        // 获取当前角色的权限
        $auth = AuthGroupItem::find()
            ->where(['group_id' => $group['id']])
            ->andWhere(['app_id' => Yii::$app->id])
            ->andWhere(['is_addon' => $is_addon])
            ->andFilterWhere(['addons_name' => $addons_name])
            ->asArray()
            ->all();

        return array_column($auth, 'name');
    }

    public function accredit($group_id, array $data, $is_addon, $app_id)
    {
        // 删除原先所有权限
        AuthGroupItem::deleteAll(['group_id' => $group_id, 'is_addon' => $is_addon]);

        if (empty($data)) {
            return;
        }

        $rows = [];
        $items = Yii::$app->services->authItem->findAllByAppId($app_id, $data);

        foreach ($items as $value) {
            $rows[] = [
                $group_id,
                $value['id'],
                $value['name'],
                $value['app_id'],
                $value['is_addon'],
                $value['addons_name'],
                $value['is_menu'],
            ];
        }

        $field = ['group_id', 'item_id', 'name', 'app_id', 'is_addon', 'addons_name', 'is_menu'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthGroupItem::tableName(), $field, $rows)->execute();
    }

    /**
     * 获取某分组的所有权限
     *
     * @param $id
     * @return array
     */
    public function getAuthByGroupId($id)
    {
        $auth = AuthGroupItem::find()
            ->where(['group_id' => $id])
            ->with(['item'])
            ->asArray()
            ->all();

        return array_column($auth, 'item');
    }

    public function getNormalGroup($app_id)
    {
        $group = AuthGroup::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['=', 'status' ,StatusEnum::ENABLED])
            ->orderBy(['sort' => SORT_DESC,'id' => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map($group,'id','title');
    }

    /**
     * 获取当前用户所在权限组
     * @return array|mixed
     */
    public function getGroup()
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        if (!$this->group) {
            /* @var $allocation \common\models\common\Allocation */
            if ($allocation = Yii::$app->user->identity->allocation) {
                $allocation = ArrayHelper::toArray($allocation);
                $this->group = AuthGroup::find()
                    ->where(['id' => $allocation['group_id']])
                    ->asArray()
                    ->one();
            }
        }

        return $this->group;
    }
}