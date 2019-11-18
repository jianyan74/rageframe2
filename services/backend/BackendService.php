<?php

namespace services\backend;

use Yii;
use common\enums\AppEnum;
use common\components\Service;

/**
 * Class BackendService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class BackendService extends Service
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

    /**
     * @param $model
     * @return string
     */
    public function getUserName($model)
    {
        switch ($model->app_id) {
            case AppEnum::BACKEND :
                return $model->backendMember->username ?? '游客';
                break;
            case AppEnum::MERCHANT :
                return $model->merchantMember->username ?? '游客';
                break;
            case AppEnum::OAUTH2 :
                return $model->oauth2Member->username ?? '游客';
                break;
            default :
                return $model->member->username ?? '游客';
                break;
        }
    }
}