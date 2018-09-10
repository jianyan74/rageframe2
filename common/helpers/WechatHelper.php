<?php
namespace common\helpers;

use Yii;
use yii\web\BadRequestHttpException;
use linslin\yii2\curl\Curl;

/**
 * 微信辅助类
 *
 * Class WechatHelper
 * @package common\helpers
 */
class WechatHelper
{
    /**
     * jsapi支付
     *
     * @param array $attributes
     * @return mixed
     * @throws BadRequestHttpException
     */
    public static function jsApiPay(array $attributes)
    {
        $attributes['trade_type'] = 'JSAPI';

        $app = Yii::$app->wechat->payment;
        $result = $app->order->unify($attributes);
        if ($result['return_code'] == 'SUCCESS')
        {
            $prepayId = $result['prepay_id'];
            $config = $app->jssdk->sdkConfig($prepayId);
            return $config;
        }

        throw new BadRequestHttpException($result['return_msg']);
    }

    /**
     * 扫码支付
     *
     * @param array $attributes
     * @return mixed
     * @throws BadRequestHttpException
     */
    public static function nativePay(array $attributes)
    {
        $attributes['trade_type'] = 'NATIVE';
        $app = Yii::$app->wechat->payment;
        $result = $app->order->unify($attributes);
        if ($result['return_code'] == 'SUCCESS')
        {
            return $result['code_url'];
        }

        throw new BadRequestHttpException($result['return_msg']);
    }

    /**
     * 验证token是否一致
     *
     * @param string $signature 微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数
     * @param integer $timestamp 时间戳
     * @param integer $nonce 随机数
     * @return bool
     */
    public static function verifyToken($signature, $timestamp, $nonce)
    {
        $token = Yii::$app->debris->config('wechat_token');
        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr == $signature ? true : false;
    }
}