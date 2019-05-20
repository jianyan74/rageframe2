<?php
namespace common\helpers;

use Yii;
use yii\helpers\Json;
use common\models\common\Provinces;

/**
 * 行政区划分同步辅助类
 *
 * 数据基于：
 *     https://github.com/modood/Administrative-divisions-of-China
 *
 * Class AdministrativeDivisionsHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AdministrativeDivisionsHelper
{
    public static $data = '';

    /**
     * @throws \yii\db\Exception
     */
    public static function insert()
    {
        $data = Json::decode(static::$data);
        $insertData = [];
        $command = Yii::$app->db->createCommand();

        foreach ($data as $datum)
        {
            $provinceId = (string) $datum['code'] . "0000";
            $insertData[] = [$provinceId, $datum['name'], 0, 1, 'tr_0'];
            foreach ($datum['children'] as $item)
            {
                $cityId = (string) $item['code'] . "00";
                $insertData[] = [$cityId, $item['name'], $provinceId, 2, 'tr_0 tr_' . $provinceId];
                foreach ($item['children'] as $value)
                {
                    $insertData[] = [$value['code'], $value['name'], $cityId, 3, 'tr_0 tr_' . $provinceId . ' tr_' . $cityId];
                }
            }
        }

        $command->batchInsert(Provinces::tableName(), ['id', 'title', 'pid', 'level', 'position'], $insertData)->execute();

        p(Json::decode(static::$data));
        die();
    }
}