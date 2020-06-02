<?php

namespace common\enums;

/**
 * 提现账号类别
 *
 * Class AccountTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AccountTypeEnum extends BaseEnum
{
    const UNION = 1;
    const WECHAT = 2;
    const ALI = 3;
    const BALANCE = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::UNION => '银联卡',
            self::WECHAT => '微信',
            self::ALI => '支付宝',
            self::BALANCE => '余额',
        ];
    }
}