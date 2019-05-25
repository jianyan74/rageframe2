<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class CreateOrderResponse
 * @package Omnipay\UnionPay\Message
 */
class CreateOrderResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['tn']);
    }


    public function getTradeNo()
    {
        return isset($this->data['tn']) ? $this->data['tn'] : null;
    }
}
