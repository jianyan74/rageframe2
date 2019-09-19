<?php

namespace common\components\payment;

use Yii;
use Omnipay\Omnipay;

/**
 * 银联支付类
 *
 * Class UnionPay
 * @package common\components\payment
 */
class UnionPay
{
    protected $config;

    const DEFAULT = 'UnionPay_Express';

    /**
     * UnionPay constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param string $type
     * @return \Omnipay\UnionPay\ExpressGateway
     */
    private function create($type = self::DEFAULT)
    {
        /* @var $gateway \Omnipay\UnionPay\ExpressGateway */
        $gateway = Omnipay::create($type);
        $gateway->setMerId($this->config['mch_id']);
        $gateway->setCertId($this->config['cert_id']);
        $gateway->setPublicKey(Yii::getAlias($this->config['public_key'])); // path or content
        $gateway->setPrivateKey(Yii::getAlias($this->config['private_key'])); // path or content
        $gateway->setReturnUrl($this->config['return_url']);
        $gateway->setNotifyUrl($this->config['notify_url']);

        return $gateway;
    }

    /**
     * 回调
     *
     * @return mixed
     */
    public function notify()
    {
        $gateway = $this->create();
        return $gateway->completePurchase([
            'request_params' => $_REQUEST
        ])->send();
    }

    /**
     * APP
     *
     * @param $order
     * @param bool $debug
     * @return mixed
     */
    public function app($order, $debug = false)
    {
        $gateway = $this->create();
        /* @var $response \Omnipay\UnionPay\Message\CreateOrderResponse */
        $response = $gateway->createOrder($order)->send();

        return $debug ? $response->getData() : $response->getTradeNo();
    }

    /**
     * PC/Wap
     *
     * @param $order
     * @param bool $debug
     * @return mixed
     */
    public function html($order, $debug = false)
    {
        $gateway = $this->create();
        /* @var $response \Omnipay\UnionPay\Message\LegacyQuickPayPurchaseResponse */
        $response = $gateway->purchase($order)->send();

        return $debug ? $response->getData() : $response->getRedirectHtml();
    }

    /**
     * 查询订单
     *
     * @param int $orderId 订单id
     * @param int $txnTime 订单交易时间
     * @param int $txnAmt 订单总费用
     * @return mixed
     */
    public function query($orderId, $txnTime, $txnAmt)
    {
        $gateway = $this->create();
        $response = $gateway->query([
            'orderId' => $orderId, //Your site trade no, not union tn.
            'txnTime' => $txnTime, //Order trade time
            'txnAmt' => $txnAmt, //Order total fee
        ])->send();

        return $response->getData();
    }

    /**
     * 查询订单
     *
     * @param int $orderId 订单id
     * @param int $txnTime 订单交易时间
     * @param int $txnAmt 订单总费用
     * @return mixed
     */
    public function close($orderId, $txnTime, $txnAmt, $queryId)
    {
        $gateway = $this->create();
        $response = $gateway->query([
            'orderId' => $orderId, //Your site trade no, not union tn.
            'txnTime' => $txnTime, //Order trade time
            'txnAmt' => $txnAmt, //Order total fee
            'queryId' => $queryId, //Order total fee
        ])->send();

        return $response->getData();
    }

    /**
     * 退款
     *
     * @param int $orderId 订单id
     * @param int $txnTime 订单交易时间
     * @param int $txnAmt 订单总费用
     * @return mixed
     */
    public function refund($orderId, $txnTime, $txnAmt, $queryId)
    {
        $gateway = $this->create();
        $response = $gateway->refund([
            'orderId' => $orderId, //Your site trade no, not union tn.
            'txnTime' => $txnTime, //Order trade time
            'txnAmt' => $txnAmt, //Order total fee
            'queryId' => $queryId, //Order total fee
        ])->send();

        return $response->getData();
    }
}
