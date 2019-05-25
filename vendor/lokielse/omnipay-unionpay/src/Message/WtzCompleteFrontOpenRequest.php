<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\UnionPay\Common\ResponseVerifyHelper;

/**
 * Class WtzCompleteFrontOpenRequest
 * @package Omnipay\UnionPay\Message
 */
class WtzCompleteFrontOpenRequest extends WtzAbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('request_params');

        return $this->getRequestParams();
    }


    /**
     * @return mixed
     */
    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setRequestParams($value)
    {
        return $this->setParameter('request_params', $value);
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
        $env        = $this->getEnvironment();
        $rootCert   = $this->getRootCert();
        $middleCert = $this->getMiddleCert();

        $data['verify_success'] = ResponseVerifyHelper::verify($data, $env, $rootCert, $middleCert);

        return $this->response = new WtzCompleteFrontOpenResponse($this, $data);
    }
}
