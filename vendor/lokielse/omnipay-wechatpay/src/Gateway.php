<?php

namespace Omnipay\WechatPay;

/**
 * Class Gateway
 * @package Omnipay\WechatPay
 */
class Gateway extends BaseAbstractGateway
{
    public function getName()
    {
        return 'WechatPay';
    }
}
