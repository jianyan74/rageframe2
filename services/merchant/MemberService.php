<?php

namespace services\merchant;

use Yii;
use common\models\merchant\Member;
use common\components\Service;

/**
 * Class MemberService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class MemberService extends Service
{
    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByIdWithAssignment($id)
    {
        return Member::find()
            ->where(['id' => $id])
            ->with('assignment')
            ->one();
    }

    /**
     * @param Member $member
     */
    public function lastLogin(Member $member)
    {
        // 记录访问次数
        $member->visit_count += 1;
        $member->last_time = time();
        $member->last_ip = Yii::$app->request->getUserIP();
        $member->save();
    }
}