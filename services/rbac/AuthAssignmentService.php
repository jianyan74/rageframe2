<?php

namespace services\rbac;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\models\rbac\AuthAssignment;

/**
 * 授权角色
 *
 * Class AuthAssignmentService
 * @package services\rbac
 * @author jianyan74 <751393839@qq.com>
 */
class AuthAssignmentService extends Service
{
    /**
     * 分配角色
     *
     * @param array $role_ids 角色id
     * @param int $user_id 用户id
     * @param string $app_id 应用id
     * @throws UnprocessableEntityHttpException
     */
    public function assign(array $role_ids, int $user_id, string $app_id)
    {
        // 移除已有的授权
        AuthAssignment::deleteAll(['user_id' => $user_id, 'app_id' => $app_id]);

        foreach ($role_ids as $role_id) {
            $model = new AuthAssignment();
            $model->user_id = $user_id;
            $model->role_id = $role_id;
            $model->app_id = $app_id;

            if (!$model->save()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }
        }
    }

    /**
     * 获取当前用户权限的下面的所有用户id
     *
     * @param $app_id
     * @return array
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function getChildIds($app_id)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        $childRoles = Yii::$app->services->rbacAuthRole->getChildes($app_id);
        $childRoleIds = ArrayHelper::getColumn($childRoles, 'id');
        if (!$childRoleIds) {
            return [-1];
        }

        $userIds = AuthAssignment::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['in', 'role_id', $childRoleIds])
            ->select('user_id')
            ->asArray()
            ->column();

        return !empty($userIds) ? $userIds : [-1];
    }

    /**
     * @param $user_id
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByUserIdAndAppId($user_id, $app_id)
    {
        return AuthAssignment::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['user_id' => $user_id])
            ->asArray()
            ->one();
    }
}