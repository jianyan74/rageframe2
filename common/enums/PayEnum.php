<?php

namespace common\enums;

/**
 * Class PayEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class PayEnum
{
    const ORDER_GROUP = 'default';
    const ORDER_GROUP_GOODS = 'goods';
    const ORDER_GROUP_RECHARGE = 'recharge';

    /**
     * 订单组别说明
     *
     * @var array
     */
    public static $orderGroupExplain = [
        self::ORDER_GROUP => '统一支付',
        self::ORDER_GROUP_GOODS => '订单商品',
        self::ORDER_GROUP_RECHARGE => '充值',
    ];

    const PAY_TYPE = 0;
    const PAY_TYPE_WECHAT = 1;
    const PAY_TYPE_ALI = 2;
    const PAY_TYPE_UNION = 3;
    const PAY_TYPE_MINI_PROGRAM = 4;
    const PAY_TYPE_USER_MONEY = 5;
    const PAY_TYPE_OFFLINE = 100;

    /**
     * 支付类型
     *
     * @var array
     */
    public static $payTypeExplain = [
        self::PAY_TYPE_WECHAT => '微信',
        self::PAY_TYPE_ALI => '支付宝',
        self::PAY_TYPE_UNION => '银联',
        self::PAY_TYPE_MINI_PROGRAM => '小程序',
        self::PAY_TYPE_USER_MONEY => '余额',
        self::PAY_TYPE_OFFLINE => '线下',
        self::PAY_TYPE => '待支付',
    ];

    /**
     * @var array
     */
    public static $payTypeAction = [
        self::PAY_TYPE_WECHAT => 'wechat',
        self::PAY_TYPE_ALI => 'alipay',
        self::PAY_TYPE_UNION => 'union',
        self::PAY_TYPE_MINI_PROGRAM => 'miniProgram',
    ];
}