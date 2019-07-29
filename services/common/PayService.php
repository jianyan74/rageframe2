<?php

namespace services\common;

use Yii;
use common\enums\PayEnum;
use common\components\Service;
use common\models\common\PayLog;
use common\helpers\StringHelper;
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
     * @param PayForm $payForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function wechat(PayForm $payForm, $baseOrder)
    {
        // 生成订单
        $order = [
            'body' => $baseOrder['body'], // 内容
            'out_trade_no' => $baseOrder['out_trade_no'], // 订单号
            'total_fee' => $baseOrder['total_fee'],
            'notify_url' => $payForm->notifyUrl, // 回调地址
        ];

        //  判断如果是js支付
        if ($payForm->tradeType == 'js') {
            $order['open_id'] = '';
        }

        //  判断如果是刷卡支付
        if ($payForm->tradeType == 'pos') {
            $order['auth_code'] = '';
        }

        // 交易类型
        $tradeType = $payForm->tradeType;
        return Yii::$app->pay->wechat->$tradeType($order);
    }

    /**
     * @param PayForm $payForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function alipay(PayForm $payForm, $baseOrder)
    {
        // 配置
        $config = [
            'notify_url' => $payForm->notifyUrl, // 支付通知回调地址
            'return_url' => $payForm->returnUrl, // 买家付款成功跳转地址
        ];

        // 生成订单
        $order = [
            'out_trade_no' => $baseOrder['out_trade_no'],
            'total_amount' => $baseOrder['total_fee'] / 100,
            'subject' => $baseOrder['body'],
        ];

        // 交易类型
        $tradeType = $payForm->tradeType;
        return [
            'config' => Yii::$app->pay->alipay($config)->$tradeType($order)
        ];
    }

    /**
     * @param PayForm $payForm
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function union(PayForm $payForm, $baseOrder)
    {
        // 配置
        $config = [
            'notify_url' => $payForm->notifyUrl, // 支付通知回调地址
            'return_url' => $payForm->returnUrl, // 买家付款成功跳转地址
        ];

        // 生成订单
        $order = [
            'orderId' => $baseOrder['out_trade_no'], //Your order ID
            'txnTime' => date('YmdHis'), //Should be format 'YmdHis'
            'orderDesc' => $baseOrder['body'], //Order Title
            'txnAmt' => $baseOrder['total_fee'], //Order Total Fee
        ];

        // 交易类型
        $tradeType = $payForm->tradeType;
        return Yii::$app->pay->union($config)->$tradeType($order);
    }

    /**
     * @param PayForm $payForm
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     */
    public function miniProgram(PayForm $payForm, $baseOrder)
    {
        // 设置appid
        Yii::$app->params['wechatPaymentConfig'] = ArrayHelper::merge(Yii::$app->params['wechatPaymentConfig'], [
            'app_id' => Yii::$app->debris->config('miniprogram_appid')
        ]);

        $orderData = [
            'trade_type' => 'JSAPI',
            'body' => $baseOrder['body'],
            // 'detail' => '支付详情',
            'notify_url' => $payForm->notifyUrl, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'out_trade_no' => $baseOrder['out_trade_no'], // 支付
            'total_fee' => $baseOrder['total_fee'],
            'openid' => '', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $payment = Yii::$app->wechat->payment;
        $result = $payment->order->unify($orderData);
        return $payment->jssdk->sdkConfig($result['prepay_id']);
    }

    /**
     * 获取订单支付日志编号
     *
     * @param int $payFee 单位分
     * @param string $orderSn 关联订单号
     * @param int $orderGroup 订单组别 如果有自己的多种订单类型请去\common\models\common\PayLog里面增加对应的常量
     * @param int $payType 支付类型 1:微信;2:支付宝;3:银联;4:微信小程序
     * @param string $tradeType 支付方式
     * @return string
     */
    public function getOutTradeNo($totalFee, string $orderSn, int $payType, $tradeType = 'JSAPI', $orderGroup = 1)
    {
        $payModel = new PayLog();
        $payModel->out_trade_no = StringHelper::randomNum(time());
        $payModel->total_fee = $totalFee;
        $payModel->order_sn = $orderSn;
        $payModel->order_group = $orderGroup;
        $payModel->pay_type = $payType;
        $payModel->trade_type = $tradeType;
        $payModel->save();

        return $payModel->out_trade_no;
    }

    /**
     * @param $outTradeNo
     * @return array|null|\yii\db\ActiveRecord|PayLog
     */
    public function findByOutTradeNo($outTradeNo)
    {
        return PayLog::find()
            ->where(['out_trade_no' => $outTradeNo])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 获取订单编号
     *
     * @param string $outTradeNo 订单号
     * @param array $data 回调数据 字段['openid', 'mch_id', 'total_fee', 'transaction_id', 'fee_type', 'trade_type', 'pay_fee']
     * @return bool|string|array
     */
    public function notify(PayLog $log, $paymentType)
    {
        $log->ip = ip2long(Yii::$app->request->userIP);
        $log->save();

        switch ($log->order_group) {
            case PayEnum::ORDER_GROUP :
                // TODO 处理订单
                return true;
                break;
            case PayEnum::ORDER_GROUP_GOODS :
                // TODO 处理充值信息
                return true;
                break;
        }
    }
}