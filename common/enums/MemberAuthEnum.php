<?php

namespace common\enums;

/**
 * 第三方授权登录
 *
 * Class MemberAuthEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MemberAuthEnum extends BaseEnum
{
    const WECHAT = 'wechat';
    const WECHAT_MP = 'wechatMp';
    const WECHAT_OP = 'wechatOp';
    const APPLE = 'apple';
    const QQ = 'qq';
    const SINA = 'sina';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::WECHAT => '微信',
            self::WECHAT_MP => '微信小程序',
            self::WECHAT_OP => '微信开放平台',
            self::APPLE => 'Apple',
            self::QQ => 'QQ',
            self::SINA => '新浪',
        ];
    }
}