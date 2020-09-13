<?php

namespace services\merchant;

use common\components\Service;
use common\enums\StatusEnum;
use common\enums\AuditStateEnum;
use common\models\merchant\CommissionWithdraw;

/**
 * Class CommissionWithdrawService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class CommissionWithdrawService extends Service
{
    /**
     * @return int|string
     */
    public function getApplyCount($merchant_id = '')
    {
        return CommissionWithdraw::find()
            ->select('id')
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['state' => AuditStateEnum::DISABLED])
            ->andFilterWhere(['id' => $merchant_id])
            ->count();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|CommissionWithdraw
     */
    public function findById($id)
    {
        return CommissionWithdraw::find()
            ->where(['id' => $id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }
}