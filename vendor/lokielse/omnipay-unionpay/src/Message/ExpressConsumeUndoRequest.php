<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Class ExpressConsumeUndoRequest
 * @package Omnipay\UnionPay\Message
 */
class ExpressConsumeUndoRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('orderId', 'txnTime', 'txnAmt', 'queryId');

        $data = array(
            'version'     => $this->getVersion(),        //版本号
            'encoding'    => $this->getEncoding(),        //编码方式
            'certId'      => $this->getCertId(),    //证书ID
            'signMethod'  => $this->getSignMethod(),        //签名方法
            'txnType'     => '31',        //交易类型
            'txnSubType'  => '00',        //交易子类
            'bizType'     => $this->getBizType(),        //业务类型
            'accessType'  => $this->getAccessType(),        //接入类型
            'channelType' => $this->getChannelType(),        //渠道类型
            'orderId'     => $this->getOrderId(),    //商户订单号，重新产生，不同于原消费
            'merId'       => $this->getMerId(),            //商户代码，请改成自己的测试商户号
            'origQryId'   => $this->getQueryId(),
            //原消费的queryId，可以从查询接口或者通知接口中获取
            'txnTime'     => $this->getTxnTime(),    //订单发送时间，重新产生，不同于原消费
            'txnAmt'      => $this->getTxnAmt(),    //交易金额，消费撤销时需和原消费一致
            'backUrl'     => $this->getNotifyUrl(),  //后台通知地址
            'reqReserved' => $this->getReqReserved(),
            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );

        $data = $this->filter($data);

        $data['signature'] = $this->sign($data);

        return $data;
    }


    public function getQueryId()
    {
        $this->getParameter('queryId');
    }


    public function setQueryId($value)
    {
        $this->setParameter('queryId', $value);
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $data = $this->httpRequest('back', $data);

        return $this->response = new ExpressResponse($this, $data);
    }
}
