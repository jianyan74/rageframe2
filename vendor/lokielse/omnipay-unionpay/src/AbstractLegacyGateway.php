<?php

namespace Omnipay\UnionPay;

use Omnipay\Common\AbstractGateway;

/**
 * Class AbstractLegacyGateway
 * @package Omnipay\UnionPay
 */
abstract class AbstractLegacyGateway extends AbstractGateway
{
    public function getDefaultParameters()
    {
        return array(
            'version'       => '1.0.0',
            'encoding'      => 'utf-8',
            'transType'     => '01',
            'orderCurrency' => '156',
            'environment'   => 'sandbox', //dev,sandbox,staging,production
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


    public function getMerAbbr()
    {
        return $this->getParameter('merAbbr');
    }


    public function setMerAbbr($value)
    {
        return $this->setParameter('merAbbr', $value);
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


    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }


    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }


    public function getTransType()
    {
        return $this->getParameter('transType');
    }


    public function setTransType($value)
    {
        return $this->setParameter('transType', $value);
    }


    public function getOrderCurrency()
    {
        return $this->getParameter('orderCurrency');
    }


    public function setOrderCurrency($value)
    {
        return $this->setParameter('orderCurrency', $value);
    }
}
