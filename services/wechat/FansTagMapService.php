<?php
namespace services\wechat;

use Yii;
use common\components\Service;
use common\models\wechat\FansTagMap;

/**
 * Class FansTagMapService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class FansTagMapService extends Service
{
    /**
     * 批量添加标签
     *
     * @param $fan_id
     * @param $data
     * @throws \yii\db\Exception
     */
    public function add($fans_id, $data)
    {
        FansTagMap::deleteAll(['fans_id' => $fans_id]);

        $field = ['fans_id', 'tag_id', 'merchant_id'];
        return Yii::$app->db->createCommand()->batchInsert(FansTagMap::tableName(), $field, $data)->execute();
    }

}