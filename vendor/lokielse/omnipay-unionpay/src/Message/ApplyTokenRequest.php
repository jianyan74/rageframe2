<?php

namespace Omnipay\UnionPay\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Class ApplyTokenRequest
 * @package Omnipay\UnionPay\Message
 */
class ApplyTokenRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('txnTime', 'orderId', 'trId');

        $data = array(
            'version'       => $this->getVersion(),  //版本号
            'encoding'      => $this->getEncoding(),  //编码方式
            'certId'        => $this->getTheCertId(),    //证书ID
            'signMethod'    => $this->getSignMethod(),  //签名方法
            'txnType'       => '79',        //交易类型
            'txnSubType'    => '05',        //交易子类
            'bizType'       => '000301',    //业务类型 000301 000902
            'accessType'    => '0',         //接入类型
            'channelType'   => '07',        //渠道类型 05:语音 07:互联网 08:移动
            'encryptCertId' => $this->getEncryptCertId(),
            'merId'         => $this->getMerId(),     //商户代码
            'orderId'       => $this->getOrderId(),     //商户订单号，填写开通并支付交易的orderId
            'txnTime'       => $this->getTxnTime(),    //订单发送时间
            'tokenPayData'  => sprintf('{trId=%s&tokenType=01}', $this->getTrId()), //标记请求者 trId
        );

        $data = $this->filter($data);

        $data['signature'] = $this->sign($data);

        var_dump($data);

        return $data;
    }


    /**
     * @return mixed
     */
    public function getTrId()
    {
        return $this->getParameter('trId');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setTrId($value)
    {
        return $this->setParameter('trId', $value);
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


    protected function getEncryptCertId()
    {
        $cert_path = UNIONPAY_PUBLIC_KEY;

        $x509data = file_get_contents($cert_path);
        openssl_x509_read($x509data);

        $cert         = new \stdClass();
        $data         = openssl_x509_parse($x509data);
        $cert->certId = $data ['serialNumber'];
        $cert->key    = $x509data;

        return $cert->certId;
    }
}
