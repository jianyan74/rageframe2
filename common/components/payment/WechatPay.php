<?php

namespace common\components\payment;

use Yii;
use yii\helpers\ArrayHelper;
use Omnipay\Omnipay;

/**
 * 微信支付类
 *
 * Class WechatPay
 * @package common\components\payment
 */
class WechatPay
{
    const DEFAULT = 'WechatPay';
    const APP = 'WechatPay_App';
    const NATIVE = 'WechatPay_Native';
    const JS = 'WechatPay_Js';
    const POS = 'WechatPay_Pos';
    const MWEB = 'WechatPay_Mweb';

    /**
     * 订单
     *
     * @var array
     */
    protected $order;

    /**
     * 配置
     *
     * @var
     */
    protected $config;

    /**
     * WechatPay constructor.
     */
    public function __construct($config)
    {
        $this->order = [
            'spbill_create_ip' => Yii::$app->request->userIP,
            'fee_type' => 'CNY',
            'notify_url' => Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['we-notify/notify']),
        ];

        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param $type
     * @return \Omnipay\WechatPay\AppGateway
     */
    private function create($type)
    {
        /* @var $gateway \Omnipay\WechatPay\AppGateway */
        $gateway = Omnipay::create($type);
        $gateway->setAppId($this->config['app_id']);
        $gateway->setMchId($this->config['mch_id']);
        $gateway->setApiKey($this->config['api_key']);
        $gateway->setCertPath($this->config['cert_client']);
        $gateway->setKeyPath($this->config['cert_key']);

        return $gateway;
    }

    /**
     * 回调
     *
     * @return \Omnipay\WechatPay\Message\CompletePurchaseResponse
     */
    public function notify()
    {
        $gateway = $this->create(self::DEFAULT);

        return $gateway->completePurchase([
            'request_params' => file_get_contents('php://input')
        ])->send();
    }

    /**
     * 微信APP支付网关
     * @param array $order
     *    [
     *        'body'              => 'The test order',
     *        'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
     *        'total_fee'         => 1, //=0.01
     *     ]
     * @param bool $debug
     * @return mixed
     */
    public function app($order, $debug = false)
    {
        $gateway = $this->create(self::APP);
        $request = $gateway->purchase(ArrayHelper::merge($this->order, $order));
        $response = $request->send();

        return $debug ? $response->getData() : $response->getAppOrderData();
    }

    /**
     * 微信原生扫码支付支付网关
     *
     * @param array $order
     *    [
     *        'body'              => 'The test order',
     *        'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
     *        'total_fee'         => 1, //=0.01
     *     ]
     * @param bool $debug
     * @return mixed
     */
    public function native($order, $debug = false)
    {
        $gateway = $this->create(self::NATIVE);
        $request = $gateway->purchase(ArrayHelper::merge($this->order, $order));
        $response = $request->send();

        return $debug ? $response->getData() : $response->getCodeUrl();
    }

    /**
     * 微信js支付支付网关
     *
     * @param array $order
     *    [
     *        'body'              => 'The test order',
     *        'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
     *        'total_fee'         => 1, //=0.01
     *        'openid'            => 'ojPztwJ5bRWRt_Ipg', //=0.01
     *     ]
     * @param bool $debug
     * @return mixed
     */
    public function js($order, $debug = true)
    {
        $gateway = $this->create(self::JS);
        $request = $gateway->purchase(ArrayHelper::merge($this->order, $order));
        $response = $request->send();

        return $debug ? $response->getData() : $response->getJsOrderData();
    }

    /**
     * 微信刷卡支付网关
     *
     * @param array $order
     *    [
     *        'body'              => 'The test order',
     *        'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
     *        'total_fee'         => 1, //=0.01,
     *        'auth_code'         => '',
     *     ]
     * @param bool $debug
     * @return mixed
     */
    public function pos($order, $debug = false)
    {
        $gateway = $this->create(self::POS);
        $request = $gateway->purchase(ArrayHelper::merge($this->order, $order));
        $response = $request->send();

        return $debug ? $response->getData() : $response->getData();
    }

    /**
     * 微信H5支付网关
     * @param array $order
     *    [
     *        'body'              => 'The test order',
     *        'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
     *        'total_fee'         => 1, //=0.01
     *     ]
     * @param bool $debug
     * @return mixed
     */
    public function mweb($order, $debug = false)
    {
        $gateway = $this->create(self::MWEB);
        $request = $gateway->purchase(ArrayHelper::merge($this->order, $order));
        $response = $request->send();

        return $debug ? $response->getData() : $response->getData();
    }

    /**
     * 关闭订单
     *
     * @param $out_trade_no
     */
    public function close($out_trade_no)
    {
        $gateway = $this->create(self::DEFAULT);
        $response = $gateway->close([
            'out_trade_no' => $out_trade_no, //The merchant trade no
        ])->send();

        return $response->getData();
    }

    /**
     * 查询订单
     *
     * @param $transaction_id
     */
    public function query($transaction_id)
    {
        $gateway = $this->create(self::DEFAULT);
        $response = $gateway->query([
            'transaction_id' => $transaction_id, //The wechat trade no
        ])->send();

        return $response->getData();
    }

    /**
     * 退款
     *
     * 订单类型
     *
     * @param $info
     * [
     *     'transaction_id' => $transaction_id, //The wechat trade no
     *     'out_refund_no'  => $outRefundNo,
     *     'total_fee'      => 1, //=0.01
     *      'refund_fee'    => 1, //=0.01
     * ]
     */
    public function refund($info)
    {
        $gateway = $this->create(self::DEFAULT);
        $response = $gateway->refund($info)->send();

        return $response->getData();
    }
}
