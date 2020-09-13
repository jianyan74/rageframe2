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
    // 海外
    const ALIH5_ALPHAPAY = 200;
    const MINIP_ALPHAPAY = 201;
    const WECHAT_ALPHAPAY = 202;
    const ALI_ALPHAPAY = 203;
    // 海外信用卡
    const STRIPE = 300;

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
            // 其他
            self::OFFLINE => '线下支付',
            self::INTEGRAL => '积分兑换',
            self::BARGAIN => '砍价',
            // 海外
            self::WECHAT_ALPHAPAY => '微信(By AlphaPay)',
            self::ALI_ALPHAPAY => '支付宝(By AlphaPay)',
            self::ALIH5_ALPHAPAY => '支付宝H5(By AlphaPay)',
            self::MINIP_ALPHAPAY => '微信小程序(By AlphaPay)',
            self::STRIPE => '信用卡支付(By Stripe)',
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
            self::WECHAT_ALPHAPAY => 'alphapay',
            self::ALI_ALPHAPAY => 'alphapay',
            self::ALIH5_ALPHAPAY => 'alphapay',
            self::MINIP_ALPHAPAY => 'alphapay',
            self::STRIPE => 'stripe',
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
            self::WECHAT_ALPHAPAY => '微信(By AlphaPay)',
            self::ALI_ALPHAPAY => '支付宝(By AlphaPay)',
            self::ALIH5_ALPHAPAY => '支付宝H5(By AlphaPay)',
            self::MINIP_ALPHAPAY => '微信小程序(By AlphaPay)',
            self::STRIPE => '信用卡支付(By Stripe)',
        ];
    }
}