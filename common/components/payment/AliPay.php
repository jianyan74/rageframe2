<?php

namespace common\components\payment;

use Yii;
use Omnipay\Omnipay;
use Omnipay\Alipay\Responses\AopTradeAppPayResponse;
use Omnipay\Alipay\Responses\AopTradePreCreateResponse;
use Omnipay\Alipay\Responses\AopTradeWapPayResponse;

/**
 * Class AliPay
 * @package common\components\payment
 */
class AliPay
{
    protected $config;

    const PC = 'Alipay_AopPage';
    const APP = 'Alipay_AopApp';
    const F2F = 'Alipay_AopF2F';
    const WAP = 'Alipay_AopWap';

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param string $type
     * @return \Omnipay\Alipay\AopPageGateway
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    private function create($type = self::PC)
    {
        /* @var $gateway \Omnipay\Alipay\AopPageGateway */
        $gateway = Omnipay::create($type);
        $gateway->setSignType('RSA2'); // RSA/RSA2/MD5
        $gateway->setAppId($this->config['app_id']);
        $gateway->setAlipayPublicKey(Yii::getAlias($this->config['ali_public_key']));
        $gateway->setPrivateKey(Yii::getAlias($this->config['private_key']));
        $gateway->setNotifyUrl($this->config['notify_url']);
        !empty($this->config['return_url']) && $gateway->setReturnUrl($this->config['return_url']);
        $this->config['sandbox'] === true && $gateway->sandbox();

        return $gateway;
    }

    /**
     * 电脑网站支付
     *
     * @param $config
     *
     * 参数说明
     * $config = [
     *     'subject'      => 'test',
     *     'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
     *     'total_amount' => '0.01',
     * ]
     *
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function pc($order, $debug = false)
    {
        $order['product_code'] = 'FAST_INSTANT_TRADE_PAY';

        $gateway = $this->create(self::PC);
        $gateway->setParameter('return_url', $this->config['return_url']);
        /** @var \Omnipay\Alipay\Requests\AopTradePagePayRequest $request */
        $request = $gateway->purchase();
        $request->setBizContent($order);
        /** @var \Omnipay\Common\Message\AbstractResponse $response */
        $response = $request->send();
        $redirectUrl = $response->getRedirectUrl();

        /**
         * 直接跳转
         * return $response->redirect();
         */
        return $debug == true ? $response->getData() : $redirectUrl;
    }

    /**
     * APP支付
     *
     * 参数说明
     * $config = [
     *     'subject'      => 'test',
     *     'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
     *     'total_amount' => '0.01',
     * ]
     *
     * iOS 客户端
     * [[AlipaySDK defaultService] payOrder:orderString fromScheme:appScheme callback:^(NSDictionary *resultDic) {
     *      NSLog(@"reslut = %@",resultDic);
     * }];
     *
     * Android 客户端
     * PayTask alipay = new PayTask(PayDemoActivity.this);
     * Map<String, String> result = alipay.payV2(orderString, true);
     * @param $config
     * @param $notifyUrl
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function app($order, $debug = false)
    {
        $order['product_code'] = 'QUICK_MSECURITY_PAY';

        $gateway = $this->create(self::APP);
        /** @var \Omnipay\Alipay\Requests\AopTradePagePayRequest $request */
        $request = $gateway->purchase();
        $request->setBizContent($order);
        /** @var AopTradeAppPayResponse $response */
        $response = $request->send();

        return $debug == true ? $response->getData() : $response->getOrderString();
    }

    /**
     * 面对面支付
     *
     * @param $order
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function f2f($order, $debug = false)
    {
        $gateway = $this->create(self::F2F);
        /** @var \Omnipay\Alipay\Requests\AopTradePagePayRequest $request */
        $request = $gateway->purchase();
        $request->setBizContent($order);
        /** @var AopTradePreCreateResponse $response */
        $response = $request->send();
        return $debug == true ? $response->getData() : $response->getQrCode();
    }

    /**
     * 手机网站支付
     *
     * @param $order
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function wap($order, $debug = false)
    {
        $order['product_code'] = 'QUICK_WAP_PAY';

        $gateway = $this->create(self::WAP);
        /** @var \Omnipay\Alipay\Requests\AopTradePagePayRequest $request */
        $request = $gateway->purchase();
        $request->setBizContent($order);
        /** @var AopTradeWapPayResponse $response */
        $response = $request->send();

        /**
         * 直接跳转
         * return $response->redirect();
         */
        return $debug == true ? $response->getData() : $response->getRedirectUrl();
    }

    /**
     * 退款
     *
     * $info = [
     *     'out_trade_no' => 'The existing Order ID',
     *     'trade_no' => 'The Transaction ID received in the previous request',
     *     'refund_amount' => 18.4,
     *     'out_request_no' => date('YmdHis') . mt_rand(1000, 9999)
     *  ]
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function refund(array $info)
    {
        $gateway = $this->create();
        $request = $gateway->refund();
        $response = $request->setBizContent($info)->send();

        return $response->getData();
    }

    /**
     * 扫码收款
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function capture()
    {
        $gateway = $this->create('Alipay_AopF2F');
        $request = $gateway->capture();

        return $request;
    }

    /**
     * 异步/同步通知
     *
     * @return \Omnipay\Alipay\Requests\AopCompletePurchaseRequest
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function notify()
    {
        $gateway = $this->create();
        $request = $gateway->completePurchase();
        $request->setParams(array_merge(Yii::$app->request->post(), Yii::$app->request->get())); // Optional

        return $request;
    }
}
