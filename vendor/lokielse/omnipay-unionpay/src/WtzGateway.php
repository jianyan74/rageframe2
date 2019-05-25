<?php

namespace Omnipay\UnionPay;

/**
 * Class WtzGateway
 * @package Omnipay\UnionPay
 */
class WtzGateway extends ExpressGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'UnionPay_Wtz';
    }


    public function getDefaultParameters()
    {
        $params = parent::getDefaultParameters();

        $params['version'] = '5.1.0';

        return $params;
    }


    /**
     * @return mixed
     */
    public function getEncryptCert()
    {
        return $this->getParameter('encryptCert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEncryptCert($value)
    {
        return $this->setParameter('encryptCert', $value);
    }


    /**
     * @return mixed
     */
    public function getMiddleCert()
    {
        return $this->getParameter('middleCert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setMiddleCert($value)
    {
        return $this->setParameter('middleCert', $value);
    }


    /**
     * @return mixed
     */
    public function getRootCert()
    {
        return $this->getParameter('rootCert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setRootCert($value)
    {
        return $this->setParameter('rootCert', $value);
    }


    /**
     * 银联侧开通：前台交易，有前台通知，后通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function frontOpen(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzFrontOpenRequest', $parameters);
    }


    /**
     * 商户侧开通：后台交易
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function backOpen(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzBackOpenRequest', $parameters);
    }


    /**
     * 开通短信：后台交易，无通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function smsOpen(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzSmsOpenRequest', $parameters);
    }


    /**
     * 前台类交易成功才会发送后台通知
     * 后台类交易（有后台通知的接口）交易结束之后成功失败都会发通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function completeFrontOpen(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzCompleteFrontOpenRequest', $parameters);
    }


    /**
     * 查询开通：后台交易
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function openQuery(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzOpenQueryRequest', $parameters);
    }


    /**
     * 消费短信：后台交易，无通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function smsConsume(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzSmsConsumeRequest', $parameters);
    }


    /**
     * 银联侧开通：前台交易，有前台通知，后通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function frontOpenConsume(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzFrontOpenConsumeRequest', $parameters);
    }


    /**
     * 消费：后台资金类交易，有同步应答和后台通知应答
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function consume(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzConsumeRequest', $parameters);
    }


    /**
     * 退货交易：后台资金类交易，有同步应答和后台通知应答
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzRefundRequest', $parameters);
    }


    /**
     * 交易状态查询交易
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function query(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzQueryRequest', $parameters);
    }


    /**
     * 申请token号：后台交易
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function applyToken(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzApplyTokenRequest', $parameters);
    }


    /**
     * 更新token号：后台交易，无通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function updateToken(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzUpdateTokenRequest', $parameters);
    }


    /**
     * 删除token号：后台交易，无通知
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function deleteToken(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\WtzDeleteTokenRequest', $parameters);
    }
}
