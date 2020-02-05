<?php

namespace services\member;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\member\Account;

/**
 * Class AccountService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class AccountService extends Service
{
    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMemberId($member_id)
    {
        return Account::find()
            ->where(['member_id' => $member_id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 获取指定商户下的用户账号统计
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSum($merchant_id = '')
    {
        return Account::find()
            ->select([
                'sum(user_money) as user_money',
                'sum(give_money) as give_money',
                'sum(user_integral) as user_integral',
                'sum(consume_money) as consume_money'
            ])
            ->where(['>', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->asArray()
            ->one();
    }
}