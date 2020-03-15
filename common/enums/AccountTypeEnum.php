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

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::UNION => '银联卡',
            // self::WECHAT => '微信', (获取openid不方便，先禁用)
            self::ALI => '支付宝',
        ];
    }
}