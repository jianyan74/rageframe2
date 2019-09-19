<?php

namespace services\sys;

use Yii;
use common\components\Service;

/**
 * Class SysService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class SysService extends Service
{
    /**
     * @return int
     * @throws \yii\db\Exception
     */
    public function getDefaultDbSize()
    {
        $db = Yii::$app->db;
        $models = $db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $mysqlSize;
    }
}