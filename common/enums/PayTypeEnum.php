<?php

namespace common\enums;

/**
 * 支付类型
 *
 * Class PayTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class PayTypeEnum extends BaseEnum
{
    const ON_LINE = 0;
    const WECHAT = 1;
    const ALI = 2;
    const UNION = 3;
    const PAY_ON_DELIVERY = 4;
    const USER_MONEY = 5;
    const TO_SHOP = 6;

    // 其他
    const OFFLINE = 100;
    const INTEGRAL = 101;
    const BARGAIN = 102;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ON_LINE => '在线支付',
            self::WECHAT => '微信',
            self::ALI => '支付宝',
            self::UNION => '银联卡',
            self::PAY_ON_DELIVERY => '货到付款',
            self::USER_MONEY => '余额支付',
            self::TO_SHOP => '到店支付',

            self::OFFLINE => '线下支付',
            self::INTEGRAL => '积分兑换',
            self::BARGAIN => '砍价',
        ];
    }

    /**
     * 调用方法
     *
     * @param $type
     * @return mixed|string
     */
    public static function action($type)
    {
        $ations = [
            self::WECHAT => 'wechat',
            self::ALI => 'alipay',
            self::UNION => 'union',
        ];

        return $ations[$type] ?? '';
    }

    /**
     * @return array
     */
    public static function thirdParty()
    {
        return [
            self::WECHAT => '微信',
            self::ALI => '支付宝',
            self::UNION => '银联卡',
        ];
    }
}