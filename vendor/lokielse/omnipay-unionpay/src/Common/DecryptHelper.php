<?php

namespace Omnipay\UnionPay\Common;

/**
 * Decrypt Util for UnionPay
 * Class DecryptHelper
 * @package Omnipay\UnionPay\Common
 */
class DecryptHelper
{
    public static function decryptCustomerInfo($payload, $cert, $pass)
    {
        $customer = self::parse(base64_decode($payload));

        if (isset($customer['encryptedInfo'])) {
            $encrypt = $customer['encryptedInfo'];
            unset($customer['encryptedInfo']);

            $data = base64_decode($encrypt);

            $decrypted = self::decrypt($data, $cert, $pass);

            parse_str($decrypted, $parsed);

            $customer = array_merge($customer, $parsed);
        }

        return $customer;
    }


    public static function decrypt($payload, $cert, $pass)
    {
        $privateKey = CertUtil::readPrivateKeyFromCert($cert, $pass);
        openssl_private_decrypt($payload, $decrypted, $privateKey);

        return $decrypted;
    }


    public static function parse($payload)
    {
        $query = substr($payload, 1, strlen($payload) - 2);

        return StringUtil::parseFuckStr($query);
    }
}
