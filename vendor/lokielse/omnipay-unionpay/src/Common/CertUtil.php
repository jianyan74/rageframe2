<?php

namespace Omnipay\UnionPay\Common;

/**
 * Cert Util for UnionPay
 * Class CertUtil
 * @package Omnipay\UnionPay\Common
 */
class CertUtil
{
    public static function readPrivateKeyFromCert($cert, $pass)
    {
        if (is_file($cert)) {
            $cert = file_get_contents($cert);
        }

        openssl_pkcs12_read($cert, $data, $pass);

        return $data['pkey'];
    }


    public static function readX509CertId($cert)
    {
        if (is_file($cert)) {
            $cert = file_get_contents($cert);
        }

        $certData = openssl_x509_parse($cert);

        return $certData['serialNumber'];
    }


    public static function getCompanyFromCert($cert)
    {
        $cn      = $cert['subject'];
        $cn      = $cn['CN'];
        $company = explode('@', $cn);

        if (count($company) < 3) {
            return null;
        }

        return $company[2];
    }
}
