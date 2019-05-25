<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\UnionPay\Common\Signer;

/**
 * Class LegacyCompletePurchaseRequest
 * @package Omnipay\UnionPay\Message
 */
class LegacyCompletePurchaseRequest extends AbstractLegacyQuickPayRequest
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

        return $this->getParameters();
    }


    private function validateData()
    {
        $this->validate('request_params');
    }


    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }


    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }


    public function getRequestParam($key)
    {
        $params = $this->getRequestParams();
        if (isset($params[$key])) {
            return $params[$key];
        } else {
            return null;
        }
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
        $data['verify_success'] = $this->isSignMatch();
        $data['is_paid']        = $data['verify_success'] && ($this->getRequestParam('respCode') == '00');

        return $this->response = new LegacyCompletePurchaseResponse($this, $data);
    }


    protected function isSignMatch()
    {
        $signature = $this->getRequestParam('signature');

        $signer = new Signer();
        $signer->setIgnores(array('signature'));

        $match = $signer->verifyWithMD5($this->getParamsToSign(), $signature, $this->getSecretKey());

        return $match;
    }


    private function getParamsToSign()
    {
        $data = $this->getRequestParams();

        unset($data['signature']);
        unset($data['signMethod']);
        unset($data['bank']);

        return $data;
    }
}
