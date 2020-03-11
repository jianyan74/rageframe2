<?php

namespace services\merchant;

use common\components\Service;
use common\enums\AccountTypeEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\merchant\BankAccount;

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
    public function findDefault()
    {
        return BankAccount::find()
            ->where([
                'status' => StatusEnum::ENABLED,
                'is_default' => StatusEnum::ENABLED,
                'merchant_id' => $this->getMerchantId()
            ])
            ->one();
    }

    /**
     * @param $id
     * @param $member_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findById($id)
    {
        return BankAccount::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    public function getMapList()
    {
        $model = $this->findIdentity();
        $map = [];

        foreach ($model as $item) {
            $tmp = [];
            $tmp[] = $item['account_type_name'];
            $tmp[] = $item['realname'];
            $tmp[] = $item['mobile'];
            if ($item['account_type'] == AccountTypeEnum::ALI) {
                $tmp[] = $item['ali_number'];
            }

            if ($item['account_type'] == AccountTypeEnum::UNION) {
                $tmp[] = $item['branch_bank_name'];
                $tmp[] = $item['account_number'];
            }

            $map[$item['id']] = implode("\r", $tmp);
        }

        return $map;
    }

    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findIdentity()
    {
        return BankAccount::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();
    }
}