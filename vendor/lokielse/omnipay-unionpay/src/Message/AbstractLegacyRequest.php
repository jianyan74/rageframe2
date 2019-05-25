<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\UnionPay\Common\Signer;

/**
 * Class AbstractLegacyRequest
 * @package Omnipay\UnionPay\Message
 */
abstract class AbstractLegacyRequest extends AbstractRequest
{
    public function getVersion()
    {
        return $this->getParameter('version');
    }


    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }


    public function getEncoding()
    {
        return $this->getParameter('encoding');
    }


    public function setEncoding($value)
    {
        return $this->setParameter('encoding', $value);
    }


    public function getMerId()
    {
        return $this->getParameter('merId');
    }


    public function setMerId($value)
    {
        return $this->setParameter('merId', $value);
    }


    public function getMerAbbr()
    {
        return $this->getParameter('merAbbr');
    }


    public function setMerAbbr($value)
    {
        return $this->setParameter('merAbbr', $value);
    }


    public function getTransType()
    {
        return $this->getParameter('transType');
    }


    public function setTransType($value)
    {
        return $this->setParameter('transType', $value);
    }


    public function getOrderAmount()
    {
        return $this->getParameter('orderAmount');
    }


    public function setOrderAmount($value)
    {
        return $this->setParameter('orderAmount', $value);
    }


    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }


    public function setOrderNumber($value)
    {
        return $this->setParameter('orderNumber', $value);
    }


    public function getOrderTime()
    {
        return $this->getParameter('orderTime');
    }


    public function setOrderTime($value)
    {
        return $this->setParameter('orderTime', $value);
    }


    public function getOrderCurrency()
    {
        return $this->getParameter('orderCurrency');
    }


    public function setOrderCurrency($value)
    {
        return $this->setParameter('orderCurrency', $value);
    }


    public function getCustomerIp()
    {
        return $this->getParameter('customerIp');
    }


    public function setCustomerIp($value)
    {
        return $this->setParameter('customerIp', $value);
    }


    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }


    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }


    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }


    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }


    public function getShowUrl()
    {
        return $this->getParameter('showUrl');
    }


    public function setShowUrl($value)
    {
        return $this->setParameter('showUrl', $value);
    }


    public function getTitle()
    {
        return $this->getParameter('title');
    }


    public function setTitle($value)
    {
        return $this->setParameter('title', $value);
    }


    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }


    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }


    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }


    public function getEndpoint($type)
    {
        return $this->endpoints[$this->getEnvironment()][$type];
    }


    protected function sign($params, $signType = 'MD5')
    {
        $signer = new Signer($params);
        $signer->setIgnores(['sign']);

        $signType = strtoupper($signType);

        if ($signType == 'MD5') {
            $sign = $signer->signWithMD5($this->getSecretKey());
        } else {
            throw new InvalidRequestException('The signType is invalid');
        }

        return $sign;
    }


    protected function filter($params)
    {
        return array_filter($params, 'strlen');
    }
}
