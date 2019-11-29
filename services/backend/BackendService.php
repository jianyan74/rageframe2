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
                if (!empty($model->backendMember)) {
                    $str = [];
                    $str[] = "ID：" . $model->backendMember->id;
                    $str[] = "账号：" . $model->backendMember->username;
                    $str[] = "姓名：" . $model->backendMember->realname;

                    return implode("<br>", $str);
                }

                return '游客';
                break;
            case AppEnum::MERCHANT :
                if (!empty($model->merchantMember)) {
                    $str = [];
                    $str[] = 'ID：' . $model->merchantMember->id;
                    $str[] = '账号：' . $model->merchantMember->username;
                    $str[] = '姓名：' . $model->merchantMember->realname;

                    return implode("<br>", $str);
                }

                return '游客';

                break;
            case AppEnum::OAUTH2 :
                if (!empty($model->oauth2Member)) {
                    $str = [];
                    $str[] = 'ID：' . $model->oauth2Member->id;
                    $str[] = '账号：' . $model->oauth2Member->username;
                    $str[] = '昵称：' . $model->oauth2Member->nickname;
                    $str[] = '姓名：' . $model->oauth2Member->realname;

                    return implode("<br>", $str);
                }

                return '游客';

                break;
            default :
                if (!empty($model->member)) {
                    $str = [];
                    $str[] = 'ID：' . $model->member->id;
                    $str[] = '账号：' . $model->member->username;
                    $str[] = '昵称：' . $model->member->nickname;
                    $str[] = '姓名：' . $model->member->realname;

                    return implode("<br>", $str);
                }

                return '游客';

                break;
        }
    }
}