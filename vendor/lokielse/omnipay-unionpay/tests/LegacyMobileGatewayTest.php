<?php

namespace Omnipay\UnionPay\Tests;

use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\UnionPay\LegacyMobileGateway;
use Omnipay\UnionPay\Message\LegacyMobilePurchaseResponse;

class LegacyMobileGatewayTest extends GatewayTestCase
{

    /**
     * @var LegacyMobileGateway $gateway
     */
    protected $gateway;

    protected $options;


    public function setUp()
    {
        parent::setUp();
        $this->gateway = Omnipay::create('UnionPay_LegacyMobile');
        $this->gateway->setMerId(UNIONPAY_MER_ID);
        $this->gateway->setSecretKey('xxxxxxx');
        $this->gateway->setReturnUrl('http://example.com/return');
        $this->gateway->setNotifyUrl('http://example.com/notify');
        $this->gateway->setEnvironment('production');
    }


    public function testPurchase()
    {
        $order = array(
            'orderNumber' => date('YmdHis'), //Your order ID
            'orderTime'   => date('YmdHis'), //Should be format 'YmdHis'
            'title'       => 'My order title', //Order Title
            'orderAmount' => '100', //Order Total Fee
        );

        /**
         * @var LegacyMobilePurchaseResponse $response
         */
        $response = $this->gateway->purchase($order)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTradeNo());
    }


    public function testCompletePurchase()
    {
        $options = array(
            'request_params' => array(
                'certId'    => UNIONPAY_CERT_ID,
                'signature' => 'xxxxxxx'
            ),
        );

        /**
         * @var LegacyMobilePurchaseResponse $response
         */
        $response = $this->gateway->completePurchase($options)->send();
        $this->assertFalse($response->isSuccessful());
    }
}
