<?php

namespace common\enums;

/**
 * 会员升级类型
 *
 * Class MemberLevelUpgradeTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MemberLevelUpgradeTypeEnum extends BaseEnum
{
    const INTEGRAL = 1;
    const CONSUMPTION_MONEY = 2;

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::INTEGRAL => '积分',
            self::CONSUMPTION_MONEY => '消费金额',
        ];
    }
}