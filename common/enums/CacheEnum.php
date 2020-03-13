<?php

namespace common\enums;

use Yii;

/**
 * Class CacheEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class CacheEnum
{
    /**
     * @return array
     */
    protected static function getMap(): array
    {
        $merchant_id = Yii::$app->services->merchant->getId();

        return [
            'config' => $merchant_id, // 公用参数
            'addonsConfig' => $merchant_id, // 插件配置
            'apiAccessToken' => $merchant_id, // 用户信息记录
            'merapiAccessToken' => $merchant_id, // 商户用户信息记录
            'wechatFansStat' => $merchant_id, // 粉丝统计缓存
            'levelList' => $merchant_id, // 会员等级
            'addons' => '', // 插件
            'addonsName' => '', // 插件名称
            'provinces' => '', // 省市区
            'ipBlacklist' => '', // ip黑名单
            'actionBehavior' => '', // 需要被记录的行为
        ];
    }

    /**
     * @param $key
     * @param string $prefix
     * @return string
     */
    public static function getPrefix($key, $prefix = '')
    {
        if (empty($prefix)) {
            $prefix = static::getMap()[$key] ?? '';
        }

        return $prefix . $key;
    }
}