<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use common\components\Service;
use common\models\common\AddonsBinding;

/**
 * Class AddonsBindingService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsBindingService extends Service
{
    /**
     * 获取所有的重组数据
     *
     * @param $names
     * @return array
     */
    public function regroupMenuByNames($names)
    {
        $list = $this->getMenuListByNames($names);
        $data = [];
        foreach ($list as $item) {
            $key = $item['addons_name'] . '|' . $item['route'];
            $data[$key] = $item;
        }

        return $data;
    }

    /**
     * @param $names
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMenuListByNames($names)
    {
        return AddonsBinding::find()
            ->where(['entry' => 'menu'])
            ->andWhere(['in', 'addons_name', $names])
            ->asArray()
            ->all();
    }

    /**
     * @param $allCover
     * @param $allMenu
     * @param $addons_name
     * @throws \yii\db\Exception
     */
    public function create($allMenu, $allCover, $addons_name)
    {
        AddonsBinding::deleteAll(['addons_name' => $addons_name]);

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
                $row = [];
                $row['title'] = $value['title'] ?? '';
                $row['route'] = $value['route'] ?? '';
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
        Yii::$app->db->createCommand()->batchInsert(AddonsBinding::tableName(), $field, $rows)->execute();
    }
}