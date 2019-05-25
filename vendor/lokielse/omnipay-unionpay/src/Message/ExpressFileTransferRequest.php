<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Class ExpressFileTransferRequest
 * @package Omnipay\UnionPay\Message
 */
class ExpressFileTransferRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('txnTime', 'fileType', 'settleDate');

        $data = array(
            'version'    => $this->getVersion(),        //版本号
            'encoding'   => $this->getEncoding(),        //编码方式
            'certId'     => $this->getCertId(),    //证书ID
            'txnType'    => '76',        //交易类型
            'signMethod' => $this->getSignMethod(),        //签名方法
            'txnSubType' => '01',        //交易子类
            'bizType'    => '000000',        //业务类型
            'accessType' => '0',        //接入类型
            'merId'      => $this->getMerId(),     //商户代码
            'settleDate' => '0119',        //清算日期
            'txnTime'    => $this->getTxnTime(),    //订单发送时间
            'fileType'   => $this->getFileType(),        //文件类型
        );

        $data = $this->filter($data);

        $data['signature'] = $this->sign($data);

        return $data;
    }


    public function getFileType()
    {
        return $this->getParameter('fileType');
    }


    public function setQueryId($value)
    {
        $this->setParameter('queryId', $value);
    }


    public function getQueryId()
    {
        return $this->getParameter('queryId');
    }


    public function setSettleDate($value)
    {
        $this->setParameter('settleDate', $value);
    }


    public function getSettleDate()
    {
        return $this->getParameter('settleDate');
    }


    public function setFileType($value)
    {
        $this->setParameter('fileType', $value);
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
