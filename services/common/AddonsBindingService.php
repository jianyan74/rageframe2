<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use common\components\Service;
use common\helpers\ArrayHelper;
use common\models\common\AddonsBinding;
use common\helpers\StringHelper;

/**
 * Class AddonsBindingService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsBindingService extends Service
{
    /**
     * 创建菜单和入口
     *
     * @param array $allMenu
     * @param array $allCover
     * @param string $addons_name
     * @throws \yii\db\Exception
     */
    public function create(array $allMenu, array $allCover, string $addons_name)
    {
        AddonsBinding::deleteAll(['addons_name' => $addons_name]);
        // 重组数组
        foreach ($allMenu as $key => $menu) {
            $allMenu[$key] = ArrayHelper::regroupMapToArr($menu);
        }

        foreach ($allCover as $key => $cover) {
            $allCover[$key] = ArrayHelper::regroupMapToArr($cover);
        }

        $rows = [];
        foreach ($allCover as $key => $item) {
            foreach ($item as $k => $value) {
                $row = [];
                $row['title'] = $value['title'] ?? '';
                $row['route'] = $value['route'] ?? '';
                $row['icon'] = $value['icon'] ?? '';
                $row['params'] = $value['params'] ?? [];
                $row['app_id'] = $key;
                $row['entry'] = 'cover';
                $row['addons_name'] = $addons_name;
                $row['params'] = Json::encode($row['params']);
                $rows[] = $row;
            }
        }

        foreach ($allMenu as $key => $item) {
            foreach ($item as $k => $value) {
                $route = $value['route'] ?? '';
                $row = [];
                $row['title'] = $value['title'] ?? '';
                $row['route'] = '/' . StringHelper::toUnderScore($addons_name) . '/' . $route;
                $row['icon'] = $value['icon'] ?? '';
                $row['params'] = $value['params'] ?? [];
                $row['app_id'] = $key;
                $row['entry'] = 'menu';
                $row['addons_name'] = $addons_name;
                $row['params'] = Json::encode($row['params']);
                $rows[] = $row;
            }
        }

        $field = ['title', 'route', 'icon', 'params', 'app_id', 'entry', 'addons_name'];
        // 批量插入数据
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AddonsBinding::tableName(), $field, $rows)->execute();
    }

    /**
     * @param array $names
     * @param string $entry
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByNames(array $names, $entry = 'menu')
    {
        return AddonsBinding::find()
            ->where(['entry' => $entry])
            ->andWhere(['in', 'addons_name', $names])
            ->asArray()
            ->all();
    }
}