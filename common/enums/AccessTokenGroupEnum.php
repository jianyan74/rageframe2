<?php

namespace common\enums;

/**
 * Class AccessTokenGroupEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenGroupEnum extends BaseEnum
{
    const DEFAULT = 'default';
    const PC = 'pc';
    const IOS = 'ios';
    const ANDROID = 'android';
    const APP = 'app';
    const H5 = 'h5';
    const WECHAT = 'wechat';
    const WECHAT_MQ = 'wechatMq';
    const ALI_MQ = 'aliMq';
    const QQ_MQ = 'qqMq';
    const BAIDU_MQ = 'baiduMq';
    const DING_TALK_MQ = 'dingTalkMq';
    const TOU_TIAO_MQ = 'touTiaoMq';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '默认',
            self::IOS => 'ios',
            self::ANDROID => 'android',
            self::APP => 'app',
            self::H5 => 'H5',
            self::PC => 'PC',
            self::WECHAT => '微信',
            self::WECHAT_MQ => '微信小程序',
            self::ALI_MQ => '支付宝小程序',
            self::QQ_MQ => 'QQ小程序',
            self::BAIDU_MQ => '百度小程序',
            self::DING_TALK_MQ => '钉钉小程序',
            self::TOU_TIAO_MQ => '头条小程序',
        ];
    }
}