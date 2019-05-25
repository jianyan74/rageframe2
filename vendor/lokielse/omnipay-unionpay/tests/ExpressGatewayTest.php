<?php

namespace Omnipay\UnionPay\Tests;

use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\UnionPay\ExpressGateway;
use Omnipay\UnionPay\Message\ExpressPurchaseResponse;

class ExpressGatewayTest extends GatewayTestCase
{
    /**
     * @var ExpressGateway
     */
    protected $gateway;

    protected $options;


    public function setUp()
    {
        parent::setUp();
        $this->gateway = Omnipay::create('UnionPay_Express');
        $this->gateway->setMerId(UNIONPAY_MER_ID);
        $this->gateway->setCertDir(UNIONPAY_CERT_DIR); // .pfx file
        $this->gateway->setCertPath(UNIONPAY_CERT_PATH); // .pfx file
        $this->gateway->setCertPassword(UNIONPAY_CERT_PASSWORD);
        $this->gateway->setReturnUrl('http://example.com/return');
        $this->gateway->setNotifyUrl('http://example.com/notify');
        $this->gateway->setEnvironment('sandbox');
    }


    public function testPurchase()
    {
        $order = array(
            'orderId' => date('YmdHis'), //Your order ID
            'txnTime' => date('YmdHis'), //Should be format 'YmdHis'
            'title'   => 'My order title', //Order Title
            'txnAmt'  => '100', //Order Total Fee
        );

        /**
         * @var ExpressPurchaseResponse
         */
        $response = $this->gateway->purchase($order)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectHtml());
    }


    public function testCompletePurchase()
    {
        $options = array(
            'request_params' => array(
                'certId'    => '68759585097',
                'signature' => 'xxxxxxx',
            ),
        );

        /**
         * @var ExpressPurchaseResponse
         */
        $response = $this->gateway->completePurchase($options)->send();
        $this->assertFalse($response->isSuccessful());
    }


    public function testQuery()
    {
        $options = array(
            'certId'  => UNIONPAY_CERT_ID,
            'orderId' => 'xxxxxxx',
            'txnTime' => date('YmdHis'),
            'txnAmt'  => '100',
        );

        /**
         * @var ExpressPurchaseResponse
         */
        $response = $this->gateway->query($options)->send();
        $this->assertFalse($response->isSuccessful());
    }


    public function testConsumeUndo()
    {
        $options = array(
            'certId'  => UNIONPAY_CERT_ID,
            'orderId' => 'xxxxxxx',
            'txnAmt'  => '100',
            'queryId' => 'XXXXX',
            'txnTime' => date('YmdHis'),
        );

        /**
         * @var ExpressPurchaseResponse
         */
        $response = $this->gateway->consumeUndo($options)->send();
        $this->assertFalse($response->isSuccessful());
    }


    public function testRefund()
    {
        $options = array(
            'certId'  => UNIONPAY_CERT_ID,
            'orderId' => '222222',
            'queryId' => '333333',
            'txnTime' => date('YmdHis'),
            'txnAmt'  => '100',
        );

        /**
         * @var ExpressPurchaseResponse
         */
        $response = $this->gateway->refund($options)->send();
        $this->assertFalse($response->isSuccessful());
    }


    public function testFileTransfer()
    {
        $options = array(
            'certId'     => UNIONPAY_CERT_ID,
            'txnTime'    => date('YmdHis'),
            'fileType'   => '00',
            'settleDate' => '0815',
        );

        $response = $this->gateway->fileTransfer($options)->send();
        $this->assertFalse($response->isSuccessful());
    }
}
