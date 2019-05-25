<?php

namespace Omnipay\UnionPay;

/**
 * Class LegacyMobileGateway
 * @package Omnipay\UnionPay
 */
class LegacyMobileGateway extends AbstractLegacyGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'UnionPay_LegacyMobile';
    }


    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\LegacyMobilePurchaseRequest', $parameters);
    }


    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\UnionPay\Message\LegacyCompletePurchaseRequest', $parameters);
    }
}
