<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use common\models\forms\CreditsLogForm;
use common\enums\PayGroupEnum;
use common\components\Service;
use common\models\common\PayLog;
use common\models\forms\PayForm;
use common\helpers\ArrayHelper;
use common\enums\PayTypeEnum;
use common\enums\StatusEnum;
use common\helpers\StringHelper;
use common\models\common\PayRefund;
use common\helpers\BcHelper;
use common\enums\WechatPayTypeEnum;

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
        $payLog->trade_type == WechatPayTypeEnum::JS && $order['openid'] = $payLog->openid;
        //  判断如果是刷卡支付
        $payLog->trade_type == WechatPayTypeEnum::POS && $order['auth_code'] = $payLog->auth_code;

        // 交易类型
        $tradeType = $payLog->trade_type;
        $result = Yii::$app->pay->wechat->$tradeType($order);
        if (empty($result)) {
            $debug = Yii::$app->pay->wechat->$tradeType($order, true);
            Yii::$app->services->actionLog->create('wechatPayError', Json::encode($debug));

            return $debug;
        }

        return $result;
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

        Yii::$app->services->actionLog->create('wechatMpPayError', Json::encode($result));

        return $result;
    }

    /**
     * AlphaPay
     *
     * 加拿大海外代理
     *
     * @param PayLog $payLog
     * @return mixed
     */
    public function alphapay(PayLog $payLog)
    {
        if (in_array($payLog->pay_type, [PayTypeEnum::MINIP_ALPHAPAY, PayTypeEnum::WECHAT_ALPHAPAY])) {
            $channel = 'Wechat';
        } elseif (in_array($payLog->pay_type, [PayTypeEnum::ALI_ALPHAPAY, PayTypeEnum::ALIH5_ALPHAPAY])) {
            $channel = 'Alipay';
        } else {
            $channel = null;
        }

        // 生成订单
        $order = [
            'pay_type' => $payLog->pay_type,
            'description' => $payLog->body, // 内容
            'order_id' => $payLog->out_trade_no, // 订单号
            'price' => $payLog->total_fee * 100,
            'notify_url' => $payLog->notify_url, // 回调地址
            'return_url' => $payLog->return_url,
            'channel' => $channel, // 3: wechat_alphapay, 4: alipay_alphapay
            'operator' => $payLog->detail, // 操作人员
        ];

        // 代理微信小程序
        if ($payLog->pay_type === PayTypeEnum::MINIP_ALPHAPAY) {
            $order['appid'] = Yii::$app->debris->backendConfig('miniprogram_appid');
            $order['customer_id'] = $payLog->openid;
        }

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->alphapay->$tradeType($order);
    }

    /**
     * Stripe
     *
     * @param PayLog $payLog
     * @return mixed
     */
    public function stripe(PayLog $payLog)
    {
        // 生成订单
        $order = [
            'amount' => $payLog->total_fee < 1 ? 1 : $payLog->total_fee,
            'currency' => 'CAD',
            'token' => $payLog->detail,
            'description' => $payLog->body,
            'returnUrl' => $payLog->return_url,
            'metadata' => [
                'order_sn' => $payLog->order_sn,
                'out_trade_no' => $payLog->out_trade_no,
            ],
            // 'paymentMethod' => '',
            'confirm' => true,
        ];

        // 交易类型
        $tradeType = $payLog->trade_type;

        return Yii::$app->pay->stripe->$tradeType($order);
    }

    /**
     * 订单退款
     *
     * @param $pay_type
     * @param $money
     * @param $out_trade_no
     * @throws UnprocessableEntityHttpException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function refund($pay_type, $money, $order_sn)
    {
        /** @var PayLog $model */
        $model = $this->findByOrderSn($order_sn);
        if (!$model) {
            throw new UnprocessableEntityHttpException('找不到支付记录');
        }

        if ($model->pay_status == StatusEnum::DISABLED) {
            throw new UnprocessableEntityHttpException('未支付');
        }

        $residueMoney = BcHelper::sub($model->pay_fee, $this->getRefundMoneyByPayId($model->id));
        if ($money > $residueMoney) {
            throw new UnprocessableEntityHttpException('退款金额不可大于支付金额');
        }

        $refund_sn = date('YmdHis') . StringHelper::random(8, true);
        $response = [];
        switch ($pay_type) {
            case PayTypeEnum::WECHAT :
                $info = [
                    'out_trade_no' => $model->out_trade_no,
                    'transaction_id' => $model->transaction_id, //The wechat trade no
                    'out_refund_no' => $refund_sn,
                    'total_fee' => $model->pay_fee * 100, //=0.01
                    'refund_fee' => $money * 100, //=0.01
                ];

                $response = Yii::$app->pay->wechat->refund($info, $model->trade_type);
                if ($response['return_code'] != 'SUCCESS' || $response['result_code'] != 'SUCCESS') {
                    throw new UnprocessableEntityHttpException($response['err_code_des']);
                }

                break;

            case PayTypeEnum::ALI :
                $info = [
                    'out_trade_no' => $model->out_trade_no,
                    'trade_no' => $model->transaction_id,
                    'refund_amount' => $money,
                    'out_request_no' => $refund_sn,
                ];

                $response = Yii::$app->pay->alipay->refund($info);
                break;
        }

        $model->refund_fee += $money;
        $model->save();

        $refund = new PayRefund();
        $refund = $refund->loadDefaultValues();
        $refund->pay_id = $model->id;
        $refund->app_id = Yii::$app->id;
        $refund->ip = Yii::$app->request->userIP ?? 0;
        $refund->order_sn = $order_sn;
        $refund->merchant_id = $model->merchant_id;
        $refund->member_id = $model->member_id;
        $refund->refund_trade_no = $refund_sn;
        $refund->refund_money = $money;
        $refund->save();
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
     * @param $pay_id
     * @return bool|int|mixed|string|null
     */
    public function getRefundMoneyByPayId($pay_id)
    {
        $money = PayRefund::find()
            ->where(['pay_id' => $pay_id])
            ->sum('refund_money');

        return empty($money) ? 0 : $money;
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