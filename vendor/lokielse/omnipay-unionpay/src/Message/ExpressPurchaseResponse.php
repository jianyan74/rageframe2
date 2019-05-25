<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class ExpressPurchaseResponse
 * @package Omnipay\UnionPay\Message
 */
class ExpressPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return true;
    }


    public function isRedirect()
    {
        return true;
    }


    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint('front');
    }


    /**
     * @return \Omnipay\Common\Message\RequestInterface|ExpressPurchaseRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectData()
    {
        return $this->data;
    }


    public function getRedirectHtml()
    {
        $action = $this->getRequest()->getEndpoint('front');
        $fields = $this->getFormFields();
        $method = $this->getRedirectMethod();

        $html = <<<eot
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>跳转中...</title>
</head>
<body onload="javascript:document.pay_form.submit();">
    <form id="pay_form" name="pay_form" action="{$action}" method="{$method}">
        {$fields}
    </form>
</body>
</html>
eot;

        return $html;
    }


    public function getRedirectForm()
    {
        $action = $this->getRequest()->getEndpoint('front');
        $fields = $this->getFormFields();
        $method = $this->getRedirectMethod();

        $html = <<<eot
    <form id="pay_form" name="pay_form" action="{$action}" method="{$method}">
        {$fields}
    </form>
    <script>
        document.pay_form.submit();
    </script>
eot;

        return $html;
    }


    public function getFormFields()
    {
        $html = '';
        foreach ($this->data as $key => $value) {
            $html .= "<input type='hidden' name='{$key}' value='{$value}'/>\n";
        }

        return $html;
    }


    /**
     * @deprecated use $gateway->createOrder() instead
     * @return null|string
     */
    public function getTradeNo()
    {
        $data = $this->getRequest()->getHttpRequest('app', $this->data);

        if (isset($data['tn'])) {
            return $data['tn'];
        } else {
            return null;
        }
    }
}
