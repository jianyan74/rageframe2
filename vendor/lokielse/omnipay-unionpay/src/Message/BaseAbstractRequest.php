<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractRequest extends AbstractRequest
{
    protected $sandboxEndpoint = 'https://101.231.204.80:5000/gateway/api/';

    protected $productionEndpoint = 'https://gateway.95516.com/gateway/api/';

    protected $methods = array(
        'front' => 'frontTransReq.do',
        'back'  => 'backTransReq.do',
        'app'   => 'appTransReq.do',
        'query' => 'queryTrans.do',
    );


    public function getEndpoint($type)
    {
        if ($this->getEnvironment() == 'production') {
            return $this->productionEndpoint . $this->methods[$type];
        } else {
            return $this->sandboxEndpoint . $this->methods[$type];
        }
    }


    public function getEnvironment()
    {
        return $this->getParameter('environment');
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


    public function getTxnSubType()
    {
        return $this->getParameter('txnSubType');
    }


    public function setTxnSubType($value)
    {
        return $this->setParameter('txnSubType', $value);
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


    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }


    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }


    public function setTxnTime($value)
    {
        return $this->setParameter('txnTime', $value);
    }


    public function getTxnTime()
    {
        return $this->getParameter('txnTime');
    }


    public function setTxnAmt($value)
    {
        return $this->setParameter('txnAmt', $value);
    }


    public function getTxnAmt()
    {
        return $this->getParameter('txnAmt');
    }


    public function setRequestType($value)
    {
        return $this->setParameter('requestType', $value);
    }


    public function getRequestType()
    {
        return $this->getParameter('requestType');
    }


    public function setDefaultPayType($value)
    {
        return $this->setParameter('defaultPayType', $value);
    }


    public function getDefaultPayType()
    {
        return $this->getParameter('defaultPayType');
    }


    public function setCertDir($value)
    {
        return $this->setParameter('certDir', $value);
    }


    public function getCertDir()
    {
        return $this->getParameter('certDir');
    }


    protected function httpRequest($method, $data)
    {
        $url  = $this->getEndpoint($method);
        $body = http_build_query($data);

        $response = $this->httpClient->post($url)/**/
        ->setBody($body, 'application/x-www-form-urlencoded')/**/
        ->send()->getBody();

        parse_str($response, $data);

        if (! is_array($data)) {
            $data = array();
        }

        return $data;
    }
}
