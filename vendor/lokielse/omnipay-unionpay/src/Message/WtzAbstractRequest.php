<?php

namespace Omnipay\UnionPay\Message;

/**
 * Class AbstractRequest
 * @package Omnipay\UnionPay\Message
 */
abstract class WtzAbstractRequest extends AbstractRequest
{

    /**
     * @return mixed
     */
    public function getEncryptCert()
    {
        return $this->getParameter('encryptCert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEncryptCert($value)
    {
        return $this->setParameter('encryptCert', $value);
    }


    /**
     * @return mixed
     */
    public function getMiddleCert()
    {
        return $this->getParameter('middleCert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setMiddleCert($value)
    {
        return $this->setParameter('middleCert', $value);
    }


    /**
     * @return mixed
     */
    public function getRootCert()
    {
        return $this->getParameter('rootCert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setRootCert($value)
    {
        return $this->setParameter('rootCert', $value);
    }


    protected function encrypt($payload)
    {
        openssl_public_encrypt($payload, $encrypted, $this->getEncryptKey());

        return base64_encode($encrypted);
    }


    protected function getEncryptKey()
    {
        $cert = $this->getEncryptCert();

        if (is_file($cert)) {
            return file_get_contents($cert);
        } else {
            return $cert;
        }
    }
}
