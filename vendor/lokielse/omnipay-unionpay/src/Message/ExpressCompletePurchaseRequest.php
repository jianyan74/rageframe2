<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\UnionPay\Common\Signer;

/**
 * Class ExpressCompletePurchaseRequest
 * @package Omnipay\UnionPay\Message
 */
class ExpressCompletePurchaseRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->getRequestParams();
    }


    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }


    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }


    public function setCertDir($value)
    {
        $this->setParameter('certDir', $value);
    }


    public function getCertDir()
    {
        return $this->getParameter('certDir');
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
        $signer = new Signer($data);
        $signer->setIgnores(array('signature'));
        $content   = $signer->getContentToSign();
        $publicKey = $this->getPublicKey();

        if (! $this->getPublicKey()) {
            $publicKey = Signer::findPublicKey($data['certId'], $this->getCertDir());
        }

        $data['verify_success'] = $signer->verifyWithRSA($content, $data['signature'], $publicKey);

        $data['is_paid'] = $data['verify_success'] && ($this->getRequestParam('respCode') == '00');

        return $this->response = new ExpressCompletePurchaseResponse($this, $data);
    }
}
