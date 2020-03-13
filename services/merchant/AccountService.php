<?php

namespace services\merchant;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\merchant\Account;

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
    public function findIdentity()
    {
        return Account::find()
            ->where(['merchant_id' => $this->getMerchantId()])
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
                'sum(accumulate_money) as accumulate_money',
            ])
            ->where(['>', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->asArray()
            ->one();
    }
}