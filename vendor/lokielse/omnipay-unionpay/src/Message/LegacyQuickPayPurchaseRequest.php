<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Class LegacyQuickPayPurchaseRequest
 * @package Omnipay\UnionPay\Message
 */
class LegacyQuickPayPurchaseRequest extends AbstractLegacyQuickPayRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();

        $data = array(
            'version'            => $this->getVersion(),
            'charset'            => $this->getEncoding(), //UTF-8, GBK等
            'merId'              => $this->getMerId(),   //无卡商户填写
            'merAbbr'            => $this->getMerAbbr(), //商户名称
            'transType'          => $this->getTransType(), //交易类型，CONSUME or PRE_AUTH
            'orderAmount'        => $this->getOrderAmount(), //交易金额
            'orderNumber'        => $this->getOrderNumber(), //订单号，必须唯一
            'orderTime'          => $this->getOrderTime(), //交易时间, YYYYmmhhddHHMMSS
            'orderCurrency'      => $this->getOrderCurrency(), //交易币种，CURRENCY_CNY=>156
            'customerIp'         => $this->getCustomerIp(), //用户IP
            'frontEndUrl'        => $this->getReturnUrl(), //前台回调URL
            'backEndUrl'         => $this->getNotifyUrl(), //后台回调URL
            'commodityUrl'       => $this->getShowUrl(),
            'commodityName'      => $this->getTitle(),
            'origQid'            => '',
            'acqCode'            => '',
            'merCode'            => '',
            'commodityUnitPrice' => '',
            'commodityQuantity'  => '',
            'commodityDiscount'  => '',
            'transferFee'        => '',
            'customerName'       => '',
            'defaultPayType'     => '',
            'defaultBankNumber'  => '',
            'transTimeout'       => '',
            'merReserved'        => '',
            'signMethod'         => 'md5',
        );

        $data = $this->filter($data);

        $data['signature'] = $this->sign($data);

        return $data;
    }


    private function validateData()
    {
        $this->validate(
            'transType',
            'orderAmount',
            'orderNumber',
            'orderTime',
            'orderCurrency', //'customerIp',
            'returnUrl',
            'notifyUrl', //'showUrl',
            'title',
            'secretKey'
        );
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new LegacyQuickPayPurchaseResponse($this, $data);
    }
}
