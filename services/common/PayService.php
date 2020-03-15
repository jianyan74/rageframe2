<?php

namespace services\common;

use Yii;
use common\models\forms\CreditsLogForm;
use common\enums\PayGroupEnum;
use common\components\Service;
use common\models\common\PayLog;
use common\models\forms\PayForm;
use common\helpers\ArrayHelper;

/**
 * Class PayService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class PayService extends Service
{
    /**
     * 微信支付
     *
     * @param PayForm $payForm
     * @param $baseOrder
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechat(PayLog $payLog)
    {
        // 小程序支付
        if ($payLog->trade_type == 'mini_program') {
            return $this->wechatMp($payLog);
        }

        // 生成订单
        $order = [
            'body' => $payLog->body, // 内容
            'out_trade_no' => $payLog->out_trade_no, // 订单号
            'total_fee' => $payLog->total_fee * 100,
            'notify_url' => $payLog->notify_url, // 回调地址
            'detail' => $payLog->detail,
        ];

        //  判断如果是js支付
        $payLog->trade_type == 'js' && $order['openid'] = $payLog->openid;
        //  判断如果是刷卡支付
        $payLog->trade_type == 'pos' && $order['auth_code'] = $payLog->auth_code;

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->wechat->$tradeType($order, false);
    }

    /**
     * 支付宝支付
     *
     * @param PayLog $payLog
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function alipay(PayLog $payLog)
    {
        // 配置
        $config = [
            'notify_url' => $payLog->notify_url, // 支付通知回调地址
            'return_url' => $payLog->return_url, // 买家付款成功跳转地址
            'sandbox' => false,
        ];

        // 生成订单
        $order = [
            'out_trade_no' => $payLog->out_trade_no,
            'total_amount' => $payLog->total_fee,
            'subject' => $payLog->body,
        ];

        // 交易类型
        $tradeType = $payLog->trade_type;

        return [
            'config' => Yii::$app->pay->alipay($config)->$tradeType($order),
        ];
    }

    /**
     * 银联支付
     *
     * @param PayForm $payForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function union(PayLog $payLog)
    {
        // 配置
        $config = [
            'notify_url' => $payLog->notify_url, // 支付通知回调地址
            'return_url' => $payLog->return_url, // 买家付款成功跳转地址
        ];

        // 生成订单
        $order = [
            'orderId' => $payLog->out_trade_no, //Your order ID
            'txnTime' => date('YmdHis'), //Should be format 'YmdHis'
            'orderDesc' => $payLog->body, //Order Title
            'txnAmt' => $payLog->total_fee, //Order Total Fee
        ];

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->union($config)->$tradeType($order);
    }

    /**
     * @param PayForm $payForm
     * @param $baseOrder
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function wechatMp(PayLog $payLog)
    {
        // 设置appid
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge(Yii::$app->params['wechatPaymentConfig'], [
            'app_id' => Yii::$app->debris->backendConfig('miniprogram_appid'),
        ]);

        $orderData = [
            'trade_type' => 'JSAPI',
            'body' => $payLog->body,
            'detail' => $payLog->detail,
            'notify_url' => $payLog->notify_url, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'out_trade_no' => $payLog->out_trade_no, // 支付
            'total_fee' => $payLog->total_fee * 100,
            'openid' => $payLog->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $payment = Yii::$app->wechat->payment;
        $result = $payment->order->unify($orderData);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            return $payment->jssdk->sdkConfig($result['prepay_id']);
        }

        return $result;
    }

    /**
     * 支付通知回调
     *
     * @param PayLog $log
     * @param $paymentType
     * @throws \yii\web\NotFoundHttpException
     */
    public function notify(PayLog $log)
    {
        $log->pay_ip = Yii::$app->request->userIP;
        $log->save();

        switch ($log->order_group) {
            case PayGroupEnum::ORDER :
                // TODO 处理订单

                // 记录消费日志
                Yii::$app->services->memberCreditsLog->consumeMoney(new CreditsLogForm([
                    'member' => Yii::$app->services->member->get($log->member_id),
                    'num' => $log->pay_fee,
                    'credit_group' => 'order',
                    'pay_type' => $log->pay_type,
                    'remark' => "【系统】订单支付",
                    'map_id' => $log->id,
                ]));

                break;
            case PayGroupEnum::RECHARGE :
                $payFee = $log['pay_fee'];
                $member = Yii::$app->services->member->get($log['member_id']);

                // 充值
                Yii::$app->services->memberCreditsLog->incrMoney(new CreditsLogForm([
                    'member' => $member,
                    'pay_type' => $log['pay_type'],
                    'num' => $payFee,
                    'credit_group' => 'recharge',
                    'remark' => "【系统】在线充值",
                    'map_id' => $log['id'],
                ]));

                // 赠送
                if (($money = Yii::$app->services->memberRechargeConfig->getGiveMoney($payFee)) > 0) {
                    Yii::$app->services->memberCreditsLog->giveMoney(new CreditsLogForm([
                        'member' => $member,
                        'pay_type' => $log['pay_type'],
                        'num' => $money,
                        'credit_group' => 'rechargeGive',
                        'remark' => "【系统】充值赠送",
                        'map_id' => $log['id'],
                    ]));
                }

                break;
        }
    }

    /**
     * @param $outTradeNo
     * @return array|null|\yii\db\ActiveRecord|PayLog
     */
    public function findByOutTradeNo($outTradeNo)
    {
        return PayLog::find()
            ->where(['out_trade_no' => $outTradeNo])
            ->one();
    }

    /**
     * @param $order_sn
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByOrderSn($order_sn)
    {
        return PayLog::find()
            ->where(['order_sn' => $order_sn])
            ->one();
    }
}