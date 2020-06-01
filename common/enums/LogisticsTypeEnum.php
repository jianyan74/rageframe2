<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class LogisticsTypeEnum extends BaseEnum
{
    const ALIYUN = 'aliyun';
    const JUHE = 'juhe';
    const KDNIAO = 'kdniao';
    const KD100 = 'kd100';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ALIYUN => '阿里云',
            self::JUHE => '聚合',
            self::KDNIAO => '快递鸟',
            self::KD100 => '快递100',
        ];
    }
}