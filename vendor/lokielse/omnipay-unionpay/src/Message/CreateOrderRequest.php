<?php

namespace Omnipay\UnionPay\Message;

/**
 * Class CreateOrderRequest
 * @package Omnipay\UnionPay\Message
 */
class CreateOrderRequest extends ExpressPurchaseRequest
{
    public function sendData($data)
    {
        $data = $this->httpRequest('app', $data);

        return $this->response = new CreateOrderResponse($this, $data);
    }
}
