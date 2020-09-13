<?php

namespace common\enums;

use yii\helpers\Html;

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
    const WECHAT_MP = 5;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::UNION => '银联卡',
            self::WECHAT => '微信',
            self::WECHAT_MP => '微信小程序',
            self::ALI => '支付宝',
            self::BALANCE => '余额',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::UNION => Html::tag('span', self::getValue(self::UNION), array_merge(
                [
                    'class' => "blue",
                ]
            )),
            self::BALANCE => Html::tag('span', self::getValue(self::BALANCE), array_merge(
                [
                    'class' => "gray",
                ]
            )),
            self::ALI => Html::tag('span', self::getValue(self::ALI), array_merge(
                [
                    'class' => "cyan",
                ]
            )),
            self::WECHAT => Html::tag('span', self::getValue(self::WECHAT), array_merge(
                [
                    'class' => "green",
                ]
            )),
            self::WECHAT_MP => Html::tag('span', self::getValue(self::WECHAT_MP), array_merge(
                [
                    'class' => "green",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }
}