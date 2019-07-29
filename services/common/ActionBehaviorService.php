<?php

namespace services\common;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\common\ActionBehavior;

/**
 * Class ActionBehaviorService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ActionBehaviorService extends Service
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return ActionBehavior::find()
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * 重组数据列表
     *
     * @return array
     */
    public function getAllData()
    {
        $list = $this->getList();
        $data = [];
        foreach ($list as $item) {
            $key = [];
            $key[] = $item['app_id'];
            $key[] = $item['url'];
            $key[] = $item['action'];
            $data[implode('|', $key)] = $item;
        }

        return $data;
    }
}