<?php

namespace services\backend;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\backend\Member;
use common\components\Service;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class MemberService extends Service
{
    /**
     * 记录访问次数
     *
     * @param Member $member
     */
    public function lastLogin(Member $member)
    {
        $member->visit_count += 1;
        $member->last_time = time();
        $member->last_ip = Yii::$app->request->getUserIP();
        $member->save();
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'username');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Member::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Member::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->one();
    }

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
}