<?php

namespace services\common;

use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\common\BankNumber;

/**
 * Class BankNumberService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class BankNumberService extends Service
{
    /**
     * @return array
     */
    public function getMap()
    {
        return ArrayHelper::map($this->findAll(), 'bank_name', 'bank_name');
    }

    /**
     * @param $bank_name
     * @return array|\yii\db\ActiveRecord|null|BankNumber
     */
    public function findByBankName($bank_name)
    {
        return BankNumber::find()
            ->where(['bank_name' => $bank_name])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return BankNumber::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->all();
    }
}