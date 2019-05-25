<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\signature;

use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;

/**
 * RsaSha1 represents 'SHAwithRSA' (also known as RSASSA-PKCS1-V1_5-SIGN with the SHA hash) signature method.
 *
 * > **Note:** This class requires PHP "OpenSSL" extension(<http://php.net/manual/en/book.openssl.php>).
 *
 * @property string $privateCertificate Private key certificate content.
 * @property string $publicCertificate Public key certificate content.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.1.3
 */
class RsaSha extends BaseMethod
{
    /**
     * @var string path to the file, which holds private key certificate.
     */
    public $privateCertificateFile;
    /**
     * @var string path to the file, which holds public key certificate.
     */
    public $publicCertificateFile;
    /**
     * @var int|string signature hash algorithm, e.g. `OPENSSL_ALGO_SHA1`, `OPENSSL_ALGO_SHA256` and so on.
     * @see http://php.net/manual/en/openssl.signature-algos.php
     */
    public $algorithm;

    /**
     * @var string OpenSSL private key certificate content.
     * This value can be fetched from file specified by [[privateCertificateFile]].
     */
    protected $_privateCertificate;
    /**
     * @var string OpenSSL public key certificate content.
     * This value can be fetched from file specified by [[publicCertificateFile]].
     */
    protected $_publicCertificate;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!function_exists('openssl_sign')) {
            throw new NotSupportedException('PHP "OpenSSL" extension is required.');
        }
    }

    /**
     * @param string $publicCertificate public key certificate content.
     */
    public function setPublicCertificate($publicCertificate)
    {
        $this->_publicCertificate = $publicCertificate;
    }

    /**
     * @return string public key certificate content.
     */
    public function getPublicCertificate()
    {
        if ($this->_publicCertificate === null) {
            $this->_publicCertificate = $this->initPublicCertificate();
        }

        return $this->_publicCertificate;
    }

    /**
     * @param string $privateCertificate private key certificate content.
     */
    public function setPrivateCertificate($privateCertificate)
    {
        $this->_privateCertificate = $privateCertificate;
    }

    /**
     * @return string private key certificate content.
     */
    public function getPrivateCertificate()
    {
        if ($this->_privateCertificate === null) {
            $this->_privateCertificate = $this->initPrivateCertificate();
        }

        return $this->_privateCertificate;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (is_int($this->algorithm)) {
            $constants = get_defined_constants(true);
            if (isset($constants['openssl'])) {
                foreach ($constants['openssl'] as $name => $value) {
                    if (strpos($name, 'OPENSSL_ALGO_') !== 0) {
                        continue;
                    }
                    if ($value === $this->algorithm) {
                        $algorithmName = substr($name, strlen('OPENSSL_ALGO_'));
                        break;
                    }
                }
            }

            if (!isset($algorithmName)) {
                throw new InvalidConfigException("Unable to determine name of algorithm '{$this->algorithm}'");
            }
        } else {
            $algorithmName = strtoupper($this->algorithm);
        }
        return 'RSA-' . $algorithmName;
    }

    /**
     * Creates initial value for [[publicCertificate]].
     * This method will attempt to fetch the certificate value from [[publicCertificateFile]] file.
     * @throws InvalidConfigException on failure.
     * @return string public certificate content.
     */
    protected function initPublicCertificate()
    {
        if (!empty($this->publicCertificateFile)) {
            if (!file_exists($this->publicCertificateFile)) {
                throw new InvalidConfigException("Public certificate file '{$this->publicCertificateFile}' does not exist!");
            }
            return file_get_contents($this->publicCertificateFile);
        }
        return '';
    }

    /**
     * Creates initial value for [[privateCertificate]].
     * This method will attempt to fetch the certificate value from [[privateCertificateFile]] file.
     * @throws InvalidConfigException on failure.
     * @return string private certificate content.
     */
    protected function initPrivateCertificate()
    {
        if (!empty($this->privateCertificateFile)) {
            if (!file_exists($this->privateCertificateFile)) {
                throw new InvalidConfigException("Private certificate file '{$this->privateCertificateFile}' does not exist!");
            }
            return file_get_contents($this->privateCertificateFile);
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function generateSignature($baseString, $key)
    {
        $privateCertificateContent = $this->getPrivateCertificate();
        // Pull the private key ID from the certificate
        $privateKeyId = openssl_pkey_get_private($privateCertificateContent, $key);
        // Sign using the key
        openssl_sign($baseString, $signature, $privateKeyId, $this->algorithm);
        // Release the key resource
        openssl_free_key($privateKeyId);

        return base64_encode($signature);
    }

    /**
     * {@inheritdoc}
     */
    public function verify($signature, $baseString, $key)
    {
        $decodedSignature = base64_decode($signature);
        // Fetch the public key cert based on the request
        $publicCertificate = $this->getPublicCertificate();
        // Pull the public key ID from the certificate
        $publicKeyId = openssl_pkey_get_public($publicCertificate);
        // Check the computed signature against the one passed in the query
        $verificationResult = openssl_verify($baseString, $decodedSignature, $publicKeyId, $this->algorithm);
        // Release the key resource
        openssl_free_key($publicKeyId);

        return ($verificationResult == 1);
    }
}
