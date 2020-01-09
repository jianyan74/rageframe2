<?php

namespace addons\RfDevTool\common\helpers;

use Yii;
use yii\helpers\Json;
use common\enums\AppEnum;
use common\models\common\Provinces;
use common\models\common\AuthItem;
use common\enums\WhetherEnum;
use yii\web\NotFoundHttpException;

/**
 * Class ImportHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class ImportHelper
{
    public static $data = '';

    /**
     * 行政区划分同步辅助
     *
     * @throws \yii\db\Exception
     * 数据基于：
     *     https://github.com/modood/Administrative-divisions-of-China
     */
    public static function areas()
    {
        $data = Json::decode(static::$data);
        $insertData = [];
        $command = Yii::$app->db->createCommand();
        foreach ($data as $datum) {
            $provinceId = (string)$datum['code'] . "0000";
            $insertData[] = [$provinceId, $datum['name'], 0, 1, 'tr_0'];

            foreach ($datum['children'] as $item) {
                $cityId = (string)$item['code'] . "00";
                $insertData[] = [$cityId, $item['name'], $provinceId, 2, 'tr_0 tr_' . $provinceId];

                foreach ($item['children'] as $value) {
                    $insertData[] = [
                        $value['code'],
                        $value['name'],
                        $cityId,
                        3,
                        'tr_0 tr_' . $provinceId . ' tr_' . $cityId
                    ];
                }
            }
        }

        $command->batchInsert(Provinces::tableName(), ['id', 'title', 'pid', 'level', 'tree'], $insertData)->execute();

        p(Json::decode(static::$data));
        die();
    }

    /**
     * @param $data
     * @param $app_id
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function auth($data, $app_id)
    {
        if (!in_array($app_id, AppEnum::getKeys())) {
            throw new NotFoundHttpException('找不到应用id');
        }

        ini_set('max_execution_time', '0');

        AuthItem::deleteAll(['app_id' => $app_id]);
        $allData = [];
        $sortArr = [];
        foreach ($data as $datum) {
            if (!empty($datum[0])) {
                !isset($sortArr[$datum[2]]['id']) && $sortArr[$datum[2]]['id'] = 0;

                if ($datum[2] == "#") {
                    $pid = 0;
                    $level = 1;
                    $tree = 'tr_0';
                } else {
                    $pid = $allData[$datum[2]]['id'];
                    $level = $allData[$datum[2]]['level'] + 1;
                    $tree = $allData[$datum[2]]['tree'] . ' tr_' . $allData[$datum[2]]['id'];
                }

                $tmp = [
                    'title' => $datum[0],
                    'name' => $datum[1],
                    'app_id' => $app_id,
                    'is_addon' => WhetherEnum::DISABLED,
                    'pid' => $pid,
                    'sort' => $sortArr[$datum[2]]['id'],
                    'level' => $level,
                    'tree' => $tree,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];

                $model = new AuthItem();
                $model->attributes = $tmp;
                if (!$model->save()) {
                    p($tmp);
                    p($model->getErrors());
                    die();
                }

                $tmp['id'] = $model->id;
                $allData[$datum[1]] = $tmp;
                $sortArr[$datum[2]]['id']++;
            }
        }

        return true;
    }
}