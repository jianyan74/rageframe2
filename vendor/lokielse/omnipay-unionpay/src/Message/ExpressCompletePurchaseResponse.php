<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class ExpressCompletePurchaseResponse
 * @package Omnipay\UnionPay\Message
 */
class ExpressCompletePurchaseResponse extends AbstractResponse
{
    public function isPaid()
    {
        return $this->data['is_paid'];
    }


    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data['verify_success'];
    }
}
