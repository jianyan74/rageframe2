<?php

namespace common\enums;

/**
 * 支付组别
 *
 * Class PayGroupEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class PayGroupEnum extends BaseEnum
{
    const ORDER = 'order';
    const ORDER_BATCH = 'orderBatch';
    const RECHARGE = 'recharge';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ORDER => '订单',
            self::ORDER_BATCH => '订单批量支付',
            self::RECHARGE => '充值',
        ];
    }
}