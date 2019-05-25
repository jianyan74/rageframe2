<?php

namespace Omnipay\UnionPay\Common;

/**
 * Response Verify Helper for UnionPay
 * Class ResponseVerifyHelper
 * @package Omnipay\UnionPay\Common
 */
class ResponseVerifyHelper
{
    public static function verify($data, $env, $rootCert, $middleCert)
    {
        if (! isset($data['signPubKeyCert'])) {
            return false;
        }

        $publicKey = $data['signPubKeyCert'];
        $certInfo  = openssl_x509_parse($publicKey);

        $cn    = CertUtil::getCompanyFromCert($certInfo);
        $union = '中国银联股份有限公司';

        if ($env == 'sandbox') {
            if (! in_array($cn, array('00040000:SIGN', $cn))) {
                return false;
            }
        } else {
            if ($cn != $union) {
                return false;
            }
        }

        if ($data['respCode'] !== '00') {
            return false;
        }

        $from = date_create('@' . $certInfo['validFrom_time_t']);
        $to   = date_create('@' . $certInfo['validTo_time_t']);
        $now  = date_create(date('Ymd'));

        $interval1 = $from->diff($now);
        $interval2 = $now->diff($to);

        if ($interval1->invert || $interval2->invert) {
            return false;
        }

        $result = openssl_x509_checkpurpose(
            $publicKey,
            X509_PURPOSE_ANY,
            array($rootCert, $middleCert)
        );

        if ($result === true) {
            $signer = new Signer($data);
            $signer->setIgnores(array('signature'));

            $hashed    = hash('sha256', $signer->getPayload());
            $signature = base64_decode($data['signature']);

            $isSuccess = openssl_verify($hashed, $signature, $publicKey, OPENSSL_ALGO_SHA256);

            return boolval($isSuccess);
        } else {
            return false;
        }
    }
}
