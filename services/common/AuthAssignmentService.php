<?php

namespace services\common;

use common\components\Service;
use common\models\common\AuthAssignment;

/**
 * Class AuthAssignmentService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AuthAssignmentService extends Service
{
    /**
     * @param $user_id
     * @param $role_id
     * @param $app_id
     */
    public function authorization($user_id, $role_id, $app_id)
    {
        // 角色授权
        AuthAssignment::deleteAll(['user_id' => $user_id, 'app_id' => $app_id]);

        $model = new AuthAssignment();
        $model->user_id = $user_id;
        $model->role_id = $role_id;
        $model->app_id = $app_id;

        return $model->save();
    }
}