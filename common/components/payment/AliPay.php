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
     * 转账
     *
     * $info = [
     *     'out_biz_no' => '转账单号',
     *     'payee_type' => '收款人账号类型', // ALIPAY_USERID:支付宝唯一号;ALIPAY_LOGONID:支付宝登录号
     *     'payee_account' => '收款人账号',
     *     'amount' => '收款金额',
     *     'payee_real_name' => '收款方真实姓名', // 非必填
     *     'remark' => '账业务的标题，用于在支付宝用户的账单里显示', // 非必填
     *  ]
     *
     * payee_type
     *     1、ALIPAY_USERID：支付宝账号对应的支付宝唯一用户号。以2088开头的16位纯数字组成。
     *     2、ALIPAY_LOGONID：支付宝登录号，支持邮箱和手机号格式。
     *
     * 老的接口：
     * https://opendocs.alipay.com/apis/api_28/alipay.fund.trans.toaccount.transfer
     *
     * 新的接口
     * https://opendocs.alipay.com/apis/api_28/alipay.fund.trans.uni.transfer/
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function transfer(array $info)
    {
        !isset($info['payee_type']) && $info['payee_type'] = 'ALIPAY_LOGONID';

        $gateway = $this->create();
        $request = $gateway->transfer();
        $response = $request->setBizContent($info)->send();

        $data = $response->getData();

        return $data['alipay_fund_trans_toaccount_transfer_response'] ?? '';
    }

    /**
     * 付款到账查询
     *
     * $info = [
     *     'out_biz_no' => '转账单号',
     *     'order_id' => '回调单号',
     *  ]
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function transferQuery(array $info)
    {
        $gateway = $this->create();
        $request = $gateway->transferQuery();
        $response = $request->setBizContent($info)->send();

        $data = $response->getData();

        return $data['alipay_fund_trans_toaccount_transfer_response'] ?? '';
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
