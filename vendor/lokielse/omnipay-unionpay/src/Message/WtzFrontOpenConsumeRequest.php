<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\UnionPay\Common\CertUtil;

/**
 * Class WtzFrontOpenConsumeRequest
 * @package Omnipay\UnionPay\Message
 */
class WtzFrontOpenConsumeRequest extends WtzFrontOpenRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('orderId', 'txnAmt', 'txnTime', 'accNo', 'payTimeout');

        $data = array(
            'version'       => $this->getVersion(),  //版本号
            'encoding'      => $this->getEncoding(),  //编码方式
            'certId'        => $this->getTheCertId(),    //证书ID
            'signMethod'    => $this->getSignMethod(),  //签名方法
            'txnType'       => '01',        //交易类型
            'txnSubType'    => '01',        //交易子类
            'bizType'       => '000902',    //业务类型
            'accessType'    => $this->getAccessType(),         //接入类型
            'channelType'   => $this->getChannelType(), //05:语音 07:互联网 08:移动
            'currencyCode'  => '156',
            'encryptCertId' => CertUtil::readX509CertId($this->getEncryptKey()),
            'merId'         => $this->getMerId(),     //商户代码
            'orderId'       => $this->getOrderId(),     //商户订单号，填写开通并支付交易的orderId
            'txnAmt'        => $this->getTxnAmt(),    //订单发送时间
            'txnTime'       => $this->getTxnTime(),    //订单发送时间
            'tokenPayData'  => sprintf('{trId=%s&tokenType=01}', $this->getTrId()), //标记请求者 trId
            'accNo'         => $this->encrypt($this->getAccNo()), //银行卡号
            'customerInfo'  => $this->getEncryptCustomerInfo(), //标记请求者 trId,
            'frontUrl'      => $this->getReturnUrl(), //前台通知地址
            'backUrl'       => $this->getNotifyUrl(), //后台通知地址
            'payTimeout'    => $this->getPayTimeout(), //订单超时时间
        );

        $data = $this->filter($data);

        $data['signature'] = $this->sign($data, 'RSA2');

        return $data;
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
        return $this->response = new WtzFrontOpenConsumeResponse($this, $data);
    }
}
