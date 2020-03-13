<?php

namespace services\merchant;

use Yii;
use common\enums\StatusEnum;
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
     * 用户
     *
     * @var Member
     */
    protected $member;

    /**
     * @param Member $member
     * @return $this
     */
    public function set(Member $member)
    {
        $this->member = $member;
        return $this;
    }

    /**
     * @param $id
     * @return array|Member|\yii\db\ActiveRecord|null
     */
    public function get($id)
    {
        if (!$this->member || $this->member['id'] != $id) {
            $this->member = $this->findById($id);
        }

        return $this->member;
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

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Member::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByMerchantId($merchant_id)
    {
        return Member::find()
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->asArray()
            ->all();
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