<?php
namespace services\member;

use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\member\Member;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class MemberService extends Service
{
    /**
     * 用户
     *
     * @var \common\models\member\Member
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
            $this->member = Member::find()
                ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->one();
        }

        return $this->member;
    }
}