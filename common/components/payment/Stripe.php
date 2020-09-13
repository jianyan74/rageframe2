<?php

namespace common\components\payment;

use Yii;
use yii\helpers\ArrayHelper;
use Omnipay\Omnipay;

/**
 * Stripe 支付类
 *
 * Class Stripe
 * @package common\components\payment
 */
class Stripe
{
    const DEFAULT = 'Stripe';
    const PI = 'Stripe_PaymentIntents';

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
     * Stripe constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param $type 
     * @return \Omnipay\Stripe\PaymentIntentsGateway
     */
    private function create($type)
    {
        /* @var $gateway \Omnipay\Stripe\PaymentIntentsGateway */
        $gateway = Omnipay::create($type);
        $gateway->initialize([
           'apiKey' => $this->config['secret_key'],
        ]);
        return $gateway;
    }

    /**
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function notify()
    {
        $gateway = $this->create(self::PI);
        $response = $gateway->completePurchase([
            'request_params' => Yii::$app->request->post()
        ])->send();

        return $response;
    }

    /**
     * Stripe Payment Intents Gateway
     *
     * @param array $order
     * @param bool $debug
     * @return mixed
     */
    public function card($order)
    {

        $gateway = $this->create(self::PI);

        $transaction = $gateway->purchase(ArrayHelper::merge($this->order, $order));
        $response = $transaction->send();
        $data = $response->getData();
        // if ($response->isSuccessful()) {
        //     $data = $response->getData();
        // } else if($response->isRedirect()) {
        //     $response->redirect();
        // } else {
        //     // The payment has failed. Use $response->getMessage() to figure out why and return to step (1).
        // }

        return empty($data['status']) ? 'fail' : $data['status'];
    }

    /**
     * 关闭订单
     *
     * @param $out_trade_no
     * @return mixed
     */
    public function close($out_trade_no)
    {
        /** @var  $gateway */
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
