<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatPay\Helper;

/**
 * Class QueryTransferRequest
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_3
 * @method  QueryTransferResponse send()
 */
class QueryTransferRequest extends BaseAbstractRequest
{
    protected $endpoint = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('app_id', 'mch_id', 'partner_trade_no', 'cert_path', 'key_path');

        $data = array(
            'appid'            => $this->getAppId(),
            'mch_id'           => $this->getMchId(),
            'partner_trade_no' => $this->getPartnerTradeNo(),
            'nonce_str'        => md5(uniqid()),
        );

        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $this->getApiKey());

        return $data;
    }


    /**
     * @return mixed
     */
    public function getPartnerTradeNo()
    {
        return $this->getParameter('partner_trade_no');
    }


    /**
     * @param mixed $partnerTradeNo
     */
    public function setPartnerTradeNo($partnerTradeNo)
    {
        $this->setParameter('partner_trade_no', $partnerTradeNo);
    }


    /**
     * @return mixed
     */
    public function getCertPath()
    {
        return $this->getParameter('cert_path');
    }


    /**
     * @param mixed $certPath
     */
    public function setCertPath($certPath)
    {
        $this->setParameter('cert_path', $certPath);
    }


    /**
     * @return mixed
     */
    public function getKeyPath()
    {
        return $this->getParameter('key_path');
    }


    /**
     * @param mixed $keyPath
     */
    public function setKeyPath($keyPath)
    {
        $this->setParameter('key_path', $keyPath);
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
        $options = array(
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERTTYPE    => 'PEM',
            CURLOPT_SSLKEYTYPE     => 'PEM',
            CURLOPT_SSLCERT        => $this->getCertPath(),
            CURLOPT_SSLKEY         => $this->getKeyPath(),
        );

        $body         = Helper::array2xml($data);
        $request      = $this->httpClient->request('POST', $this->endpoint, $options, $body);
        $response     = $request->getBody();
        $responseData = Helper::xml2array($response);

        return $this->response = new QueryTransferResponse($this, $responseData);
    }
}
