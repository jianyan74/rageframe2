<?php

namespace Omnipay\Alipay\Responses;

class LegacyAppPurchaseResponse extends AbstractLegacyResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }


    public function getOrderString()
    {
        return $this->data['order_string'];
    }
}
