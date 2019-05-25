<?php

namespace Omnipay\UnionPay;

use Omnipay\Common\AbstractGateway;

/**
 * Class ExpressGateway
 * @package Omnipay\UnionPay
 */
class ExpressGateway extends AbstractGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'UnionPay_Express';
    }


    public function getDefaultParameters()
    {
        return array(
            'version'        => '5.0.0',
            'encoding'       => 'utf-8',
            'txnType'        => '01',
            'txnSubType'     => '01',
            'bizType'        => '000201',
            'signMethod'     => '01',
            'channelType'    => '08', //07-PC，08-手机
            'accessType'     => '0',
            'currencyCode'   => '156',
            'orderDesc'      => 'an order',
            'reqReserved'    => '',
            'defaultPayType' => '0001',
            'environment'    => 'sandbox',
        );
    }


    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }


    public function getVersion()
    {
        return $this->getParameter('version');
    }


    public function setEncoding($value)
    {
        return $this->setParameter('encoding', $value);
    }


    public function getEncoding()
    {
        return $this->getParameter('encoding');
    }


    public function setTxnType($value)
    {
        return $this->setParameter('txnType', $value);
    }


    public function getTxnType()
    {
        return $this->getParameter('txnType');
    }


    public function setTxnSubType($value)
    {
        return $this->setParameter('txnSubType', $value);
    }


    public function getTxnSubType()
    {
        return $this->getParameter('txnSubType');
    }


    public function setBizType($value)
    {
        return $this->setParameter('bizType', $value);
    }


    public function getBizType()
    {
        return $this->getParameter('bizType');
    }


    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }


    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }


    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }


    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }


    public function setSignMethod($value)
    {
        return $this->setParameter('signMethod', $value);
    }


    public function getSignMethod()
    {
        return $this->getParameter('signMethod');
    }


    public function setChannelType($value)
    {
        return $this->setParameter('channelType', $value);
    }


    public function getChannelType()
    {
        return $this->getParameter('channelType');
    }


    public function setAccessType($value)
    {
        return $this->setParameter('accessType', $value);
    }


    public function getAccessType()
    {
        return $this->getParameter('accessType');
    }


    public function setMerId($value)
    {
        return $this->setParameter('merId', $value);
    }


    public function getMerId()
    {
        return $this->getParameter('merId');
    }


    public function setCurrencyCode($value)
    {
        return $this->setParameter('currencyCode', $value);
    }


    public function getCurrencyCode()
    {
        return $this->getParameter('currencyCode');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }


    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setCertDir($value)
    {
        return $this->setParameter('certDir', $value);
    }


    public function getCertDir()
    {
        return $this->getParameter('certDir');
    }


    public function setCertPath($value)
    {
        return $this->setParameter('certPath', $value);
    }


    public function getCertPath()
    {
        return $this->getParameter('certPath');
    }


    public function setCertPassword($value)
    {
        return $this->setParameter('certPassword', $value);
    }


    public function getCertPassword()
    {
        return $this->getParameter('certPassword');
    }


    public function setOrderDesc($value)
    {
        return $this->setParameter('orderDesc', $value);
    }


    public function getOrderDesc()
    {
        return $this->getParameter('orderDesc');
    }


    public function setReqReserved($value)
    {
        return $this->setParameter('reqReserved', $value);
    }


    public function getReqReserved()
    {
        return $this->getParameter('reqReserved');
    }


    public function setDefaultPayType($value)
    {
        return $this->setParameter('defaultPayType', $value);
    }


    public function getDefaultPayType()
    {
        return $this->getParameter('defaultPayType');
    }


    /**
     * @return mixed
     */
    public function getCertId()
    {
        return $this->getParameter('cert_id');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setCertId($value)
    {
        return $this->setParameter('cert_id', $value);
    }


    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
    }


    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->getParameter('public_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('public_key', $value);
    }


    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\ExpressPurchaseRequest', $parameters);
    }


    public function createOrder(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\CreateOrderRequest', $parameters);
    }


    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\ExpressCompletePurchaseRequest', $parameters);
    }


    public function query(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\ExpressQueryRequest', $parameters);
    }


    public function consumeUndo(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\ExpressConsumeUndoRequest', $parameters);
    }


    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\ExpressRefundRequest', $parameters);
    }


    public function fileTransfer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\ExpressFileTransferRequest', $parameters);
    }
}
