<?php

namespace services\member;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\member\RechargeConfig;

/**
 * Class RechargeConfigService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class RechargeConfigService extends Service
{
    /**
     * 获取赠送金额
     *
     * @param $money
     * @return int
     */
    public function getGiveMoney($money)
    {
        $model = RechargeConfig::find()
            ->where(['<=', 'price', $money])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('price desc')
            ->one();

        return $model['give_price'] ?? 0;
    }
}