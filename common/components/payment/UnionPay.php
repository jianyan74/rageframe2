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
    protected $_config;

    /**
     * UnionPay constructor.
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * 实例化类
     *
     * @param $type
     * @return mixed
     */
    private function create($type = 'UnionPay_Express')
    {
        $gateway = Omnipay::create($type);
        $gateway->setMerId($this->_config['mch_id']);
        $gateway->setCertId($this->_config['cert_id']);
        $gateway->setPublicKey($this->_config['public_key']); // path or content
        $gateway->setPrivateKey($this->_config['private_key']); // path or content
        $gateway->setReturnUrl($this->_config['return_url']);
        $gateway->setNotifyUrl($this->_config['notify_url']);

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
     * @param $order
     * @param bool $debug
     * @return mixed
     */
    public function app($order, $debug = false)
    {
        $gateway = $this->create();
        $response = $gateway->createOrder($order)->send();

        return $debug ? $response->getData() : $response->getTradeNo();
    }

    /**
     * PC/Wap
     * @param $order
     * @param bool $debug
     * @return mixed
     */
    public function html($order, $debug = false)
    {
        $gateway = $this->create();
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
            'txnAmt'  => $txnAmt, //Order total fee
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
