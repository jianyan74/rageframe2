<?php

namespace services\merchant;

use common\components\Service;
use common\models\merchant\CommissionWithdraw;

/**
 * Class CommissionWithdrawService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class CommissionWithdrawService extends Service
{
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