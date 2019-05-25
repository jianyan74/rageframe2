<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Class LegacyMobilePurchaseRequest
 * @package Omnipay\UnionPay\Message
 */
class LegacyMobilePurchaseRequest extends AbstractLegacyMobileRequest
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
            'version'          => $this->getVersion(),
            'charset'          => $this->getEncoding(),
            'transType'        => $this->getTransType(),
            'merId'            => $this->getMerId(),
            'backEndUrl'       => $this->getNotifyUrl(),
            'frontEndUrl'      => $this->getReturnUrl(),
            'orderDescription' => $this->getTitle(),
            'orderTime'        => $this->getOrderTime(),
            'orderTimeout'     => $this->getOrderTimeout(),
            'orderNumber'      => $this->getOrderNumber(),
            'orderAmount'      => $this->getOrderAmount(),
            'orderCurrency'    => $this->getOrderCurrency(),
            'reqReserved'      => '',
            'signMethod'       => 'md5',
        );

        $data = $this->filter($data);

        $data['signature'] = $this->sign($data);

        return $data;
    }


    private function validateData()
    {
        $this->validate(
            'version',
            'encoding',
            'transType',
            'merId', //'returnUrl',
            'notifyUrl',
            'title',
            'orderTime', //'orderTimeout',
            'orderNumber',
            'orderAmount',
            'orderCurrency',
            'secretKey',
            'environment'
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
        $data = $this->httpRequest('trade', $data);

        return $this->response = new LegacyMobilePurchaseResponse($this, $data);
    }
}
