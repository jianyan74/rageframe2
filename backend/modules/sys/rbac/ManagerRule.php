<?php
namespace backend\modules\sys\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Class ManagerRule
 * @package backend\rbac
 */
class ManagerRule extends Rule
{
    public $name = 'manager';

    /**
     * @param int|string $user 当前登录用户的uid
     * @param \yii\rbac\Item $item 所属规则rule，也就是我们后面要进行的新增规则
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