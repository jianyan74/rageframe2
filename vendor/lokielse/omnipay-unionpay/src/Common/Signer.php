<?php

namespace Omnipay\UnionPay\Common;

use Exception;

/**
 * Sign Tool for UnionPay
 * Class Signer
 * @package Omnipay\UnionPay\Common
 */
class Signer
{
    const ENCODE_POLICY_QUERY = 'QUERY';

    const KEY_TYPE_PUBLIC = 1;
    const KEY_TYPE_PRIVATE = 2;

    protected $ignores = array('signature', 'signMethod');

    protected $sort = true;

    protected $encodePolicy = self::ENCODE_POLICY_QUERY;

    /**
     * @var array
     */
    private $params;


    public function __construct(array $params = array())
    {
        $this->params = $params;
    }


    public function signWithMD5($key)
    {
        $content = $this->getContentToSign();

        return md5($content . $key);
    }


    public function getPayload()
    {
        $params = $this->getParamsToSign();

        return urldecode(http_build_query($params));
    }


    public function getContentToSign($alg = OPENSSL_ALGO_SHA1)
    {
        if ($this->encodePolicy == self::ENCODE_POLICY_QUERY) {
            if ($alg == OPENSSL_ALGO_SHA1) {
                return hash('sha1', $this->getPayload());
            } else {
                return hash('sha256', $this->getPayload());
            }
        } else {
            return null;
        }
    }


    /**
     * @return mixed
     */
    public function getParamsToSign()
    {
        $params = $this->params;

        $this->unsetKeys($params);

        $params = $this->filter($params);

        if ($this->sort) {
            $this->sort($params);
        }

        return $params;
    }


    /**
     * @param $params
     */
    protected function unsetKeys(&$params)
    {
        foreach ($this->getIgnores() as $key) {
            unset($params[$key]);
        }
    }


    /**
     * @return array
     */
    public function getIgnores()
    {
        return $this->ignores;
    }


    /**
     * @param array $ignores
     *
     * @return $this
     */
    public function setIgnores($ignores)
    {
        $this->ignores = $ignores;

        return $this;
    }


    private function filter($params)
    {
        return array_filter($params, 'strlen');
    }


    /**
     * @param $params
     */
    protected function sort(&$params)
    {
        ksort($params);
    }


    public function signWithRSA($privateKey, $alg = OPENSSL_ALGO_SHA1)
    {
        $content = $this->getContentToSign($alg);

        return $this->signContentWithRSA($content, $privateKey, $alg);
        ;
    }


    public function signWithCert($cert, $password, $alg)
    {
        $privateKey = $this->readPrivateKey($cert, $password);

        return $this->signWithRSA($privateKey, $alg);
    }


    protected function signContentWithRSA($content, $privateKey, $alg = OPENSSL_ALGO_SHA1)
    {
        $privateKey = $this->prefix($privateKey);
        $privateKey = $this->format($privateKey, self::KEY_TYPE_PRIVATE);
        $res        = openssl_pkey_get_private($privateKey);

        $sign = null;

        try {
            openssl_sign($content, $sign, $res, $alg);
        } catch (Exception $e) {
            if ($e->getCode() == 2) {
                $message = $e->getMessage();
                $message .= "\n应用私钥格式有误，见 https://github.com/lokielse/omnipay-unionpay/wiki/FAQs";
                throw new Exception($message, $e->getCode(), $e);
            }
        }

        openssl_free_key($res);
        $sign = base64_encode($sign);

        return $sign;
    }


    /**
     * Prefix the key path with 'file://'
     *
     * @param $key
     *
     * @return string
     */
    private function prefix($key)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN' && is_file($key) && substr($key, 0, 7) != 'file://') {
            $key = 'file://' . $key;
        }

        return $key;
    }


    /**
     * Convert key to standard format
     *
     * @param $key
     * @param $type
     *
     * @return string
     */
    public function format($key, $type)
    {
        if (is_file($key)) {
            $key = file_get_contents($key);
        }

        if (is_string($key) && strpos($key, '-----') === false) {
            $key = $this->convertKey($key, $type);
        }

        return $key;
    }


    /**
     * Convert one line key to standard format
     *
     * @param $key
     * @param $type
     *
     * @return string
     */
    public function convertKey($key, $type)
    {
        $lines = array();

        if ($type == self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----BEGIN PUBLIC KEY-----';
        } else {
            $lines[] = '-----BEGIN RSA PRIVATE KEY-----';
        }

        for ($i = 0; $i < strlen($key); $i += 64) {
            $lines[] = trim(substr($key, $i, 64));
        }

        if ($type == self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----END PUBLIC KEY-----';
        } else {
            $lines[] = '-----END RSA PRIVATE KEY-----';
        }

        return implode("\n", $lines);
    }


    public function verifyWithMD5($content, $sign, $key)
    {
        if (is_array($content)) {
            ksort($content);
            $query   = http_build_query($content);
            $content = urldecode($query);
        }

        return md5($content . '&' . $key) == $sign;
    }


    public function verifyWithRSA($content, $sign, $publicKey, $alg = OPENSSL_ALGO_SHA1)
    {
        $publicKey = $this->prefix($publicKey);
        $publicKey = $this->format($publicKey, self::KEY_TYPE_PUBLIC);

        $res = openssl_pkey_get_public($publicKey);

        if (! $res) {
            $message = "The public key is invalid";
            $message .= "\n银联公钥格式有误，见 https://github.com/lokielse/omnipay-unionpay/wiki/FAQs";
            throw new Exception($message);
        }

        $result = (bool) openssl_verify($content, base64_decode($sign), $res, $alg);

        openssl_free_key($res);

        return $result;
    }


    /**
     * @param boolean $sort
     *
     * @return Signer
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }


    /**
     * @param int $encodePolicy
     *
     * @return Signer
     */
    public function setEncodePolicy($encodePolicy)
    {
        $this->encodePolicy = $encodePolicy;

        return $this;
    }


    protected static function readPrivateKey($cert, $password)
    {
        return CertUtil::readPrivateKeyFromCert($cert, $password);
    }


    public static function findPublicKey($certId, $folder)
    {
        $handle = opendir($folder);
        if ($handle) {
            while ($file = readdir($handle)) {
                $filename = rtrim($folder, '/\\') . '/' . $file;
                if (is_file($filename) && preg_match('#\.cer$#', $filename)) {
                    if (self::readCertId($filename) == $certId) {
                        closedir($handle);

                        return file_get_contents($filename);
                    }
                }
            }
            throw new \Exception(sprintf('Can not find certId in folder %s', $folder));
        } else {
            throw new \Exception('folder is not exists');
        }
    }


    public static function readCertId($file)
    {
        return CertUtil::readX509CertId($file);
    }


    public static function readCert($file, $password)
    {
        $data = file_get_contents($file);
        openssl_pkcs12_read($data, $certs, $password);

        return $certs['cert'];
    }
}
