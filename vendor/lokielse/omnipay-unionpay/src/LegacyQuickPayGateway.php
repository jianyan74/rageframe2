<?php

namespace Omnipay\UnionPay;

/**
 * Class LegacyQuickPayGateway
 * @package Omnipay\UnionPay
 */
class LegacyQuickPayGateway extends AbstractLegacyGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'UnionPay_LegacyQuickPay';
    }


    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\LegacyQuickPayPurchaseRequest', $parameters);
    }


    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\LegacyCompletePurchaseRequest', $parameters);
    }
}
