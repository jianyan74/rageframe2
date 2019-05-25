<?php

namespace Omnipay\Alipay\Responses;

use Omnipay\Alipay\Requests\AopTradeRefundRequest;

class AopTradeRefundResponse extends AbstractAopResponse
{
    protected $key = 'alipay_trade_refund_response';

    /**
     * @var AopTradeRefundRequest
     */
    protected $request;
}
