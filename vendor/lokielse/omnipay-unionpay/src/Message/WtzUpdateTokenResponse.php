<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\UnionPay\Common\DecryptHelper;

/**
 * Class WtzUpdateTokenResponse
 * @package Omnipay\UnionPay\Message
 */
class WtzUpdateTokenResponse extends AbstractResponse
{
    /**
     * @var WtzUpdateTokenRequest
     */
    protected $request;


    public function isSuccessful()
    {
        return isset($this->data['respCode']) && $this->data['respCode'] == '00' && $this->data['verify_success'];
    }


    public function getCustomerInfo()
    {
        $cert = $this->request->getCertPath();
        $pass = $this->request->getCertPassword();

        return DecryptHelper::decryptCustomerInfo($this->data['customerInfo'], $cert, $pass);
    }
}
