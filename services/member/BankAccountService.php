<?php

namespace services\member;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\member\BankAccount;

/**
 * Class BankAccountService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class BankAccountService extends Service
{
    /**
     * 获取默认地址
     *
     * @param $member_id
     * @return array|null|\yii\db\ActiveRecord|BankAccount
     */
    public function findDefaultByMemberId($member_id)
    {
        return BankAccount::find()
            ->where([
                'member_id' => $member_id,
                'status' => StatusEnum::ENABLED,
                'is_default' => StatusEnum::ENABLED
            ])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $id
     * @param $member_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findById($id, $member_id)
    {
        return BankAccount::find()
            ->where(['id' => $id, 'member_id' => $member_id, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByMemberId($member_id)
    {
        return BankAccount::find()
            ->where(['member_id' => $member_id, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy(['is_default desc'])
            ->asArray()
            ->all();
    }
}