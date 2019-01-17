<?php
namespace backend\modules\sys\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * RBAC规则案例
 *
 * Class ManagerRule
 * @package backend\modules\sys\rbac
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerRule extends Rule
{
    public $name = 'manager';

    /**
     * @param int|string $user 当前登录用户的uid
     * @param \yii\rbac\Item $item 所属规则rule，也就是我们后面要进行的创建规则
     * @param array $params 当前请求携带的参数.
     * @return bool true 用户可访问 false用户不可访问
     */
    public function execute($user, $item, $params)
    {
        if (Yii::$app->user->id != Yii::$app->params['adminAccount'])
        {
            return false;
        }

        return true;
    }
}