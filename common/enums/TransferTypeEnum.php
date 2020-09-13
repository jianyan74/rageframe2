<?php

namespace common\enums;

/**
 * 转账类型
 *
 * Class TransferTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TransferTypeEnum extends BaseEnum
{
    const OFFLINE = 1;
    const BALANCE = 2;
    const WECHAT = 3;
    const ALI = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::OFFLINE => '线下转账',
            self::BALANCE => '在线(余额)转账',
            self::WECHAT => '微信转账',
            self::ALI => '支付宝转账',
        ];
    }
}