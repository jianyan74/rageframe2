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
    const WECHAT_MP = 'wechatMp';
    const ALI_MP = 'aliMp';
    const QQ_MP = 'qqMp';
    const BAIDU_MP = 'baiduMp';
    const DING_TALK_MP = 'dingTalkMp';
    const TOU_TIAO_MP = 'touTiaoMp';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '默认',
            self::IOS => 'iOS',
            self::ANDROID => 'Android',
            self::APP => 'App',
            self::H5 => 'H5',
            self::PC => 'PC',
            self::WECHAT => '微信',
            self::WECHAT_MP => '微信小程序',
            self::ALI_MP => '支付宝小程序',
            self::QQ_MP => 'QQ小程序',
            self::BAIDU_MP => '百度小程序',
            self::DING_TALK_MP => '钉钉小程序',
            self::TOU_TIAO_MP => '头条小程序',
        ];
    }
}