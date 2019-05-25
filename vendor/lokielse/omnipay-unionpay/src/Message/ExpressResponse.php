<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class ExpressResponse
 * @package Omnipay\UnionPay\Message
 */
class ExpressResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return isset($this->data['respCode']) && $this->data['respCode'] == '00';
    }
}
