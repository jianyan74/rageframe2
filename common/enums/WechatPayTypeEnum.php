<?php

namespace common\enums;

/**
 * 微信支付类型
 *
 * Class WechatPayTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WechatPayTypeEnum extends BaseEnum
{
    const JS = 'js';
    const APP = 'app';
    const NATIVE = 'native';
    const POS = 'pos';
    const M_WEB = 'mweb';
    const MINI_PROGRAM = 'mini_program';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::JS => 'H5',
            self::APP => 'app',
            self::NATIVE => '扫码',
            self::POS => '刷卡',
            self::M_WEB => '手机',
            self::MINI_PROGRAM => '小程序',
        ];
    }
}