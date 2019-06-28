<?php

namespace common\helpers;

use yii\web\UnprocessableEntityHttpException;

/**
 * Class EncryptionHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class EncryptionHelper
{
    /**
     * rsa加密
     * openssl genrsa -out rsa_private_key.pem 1024 // 生成原始 RSA私钥文件 rsa_private_key.pem
     * openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem // 将原始 RSA私钥转换为 pkcs8格式
     * openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem // 生成RSA公钥 rsa_public_key.pem
     *
     * @param string $data 数据
     * @param string $rsaPrivateKey 私钥PEM文件的绝对路径
     * @return string
     */
    public static function rsaEnCode($data, $rsaPrivateKey)
    {
        /* 获取私钥PEM文件内容 */
        $priKey = file_get_contents($rsaPrivateKey);
        /* 从PEM文件中提取私钥 */
        $res = openssl_get_privatekey($priKey);
        /* 对数据进行签名 */
        //openssl_sign($data, $sign, $res);
        openssl_private_encrypt($data, $sign, $res);
        /* 释放资源 */
        openssl_free_key($res);
        /* 对签名进行Base64编码，变为可读的字符串 */
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * rsa解密
     *
     * @param string $data 加密后的数据
     * @param string $rsaPublicKey 公钥PEM文件的绝对路径
     * @return mixed
     */
    public static function rsaDeCode($data, $rsaPublicKey)
    {
        /* 获取公钥PEM文件内容 */
        $pubKey = file_get_contents($rsaPublicKey);
        /* 从PEM文件中提取公钥 */
        $res = openssl_get_publickey($pubKey);
        /* 对数据进行解密 */
        openssl_public_decrypt(base64_decode($data), $decrypted, $res);
        /* 释放资源 */
        openssl_free_key($res);
        return $decrypted;
    }

    /**
     * 创建参数(包括签名的处理)
     *
     * $paramArr = [
     *     'time' => time(),
     *     'nonceStr' => \common\helpers\StringHelper::random(8),
     *     'appId' => 'doormen',
     *  ]
     * @param array $paramArr 变量参数
     * @param string $secret 秘钥(appSecret)
     * @return string
     */
    public static function createUrlParam(array $paramArr, $secret, $signName = 'sign')
    {
        $paraStr = "";
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $paraStr .= $key . '=' . urlencode($val) . '&';
            }
        }

        // 去掉最后一个&
        $paraStr = substr($paraStr, 0, strlen($paraStr) - 1);

        $signStr = $paraStr . $secret;// 排好序的参数加上secret,进行md5
        $sign = strtolower(md5($signStr));

        $paraStr .= '&';
        $paraStr .= $signName . '=' . $sign;// 将md5后的值作为参数,便于服务器的效验

        return $paraStr;
    }

    /**
     * 解密url
     *
     * @param array $paramArr
     * @param $secret
     * @param string $signName
     * @return bool
     * @throws UnprocessableEntityHttpException
     */
    public static function decodeUrlParam(array $paramArr, $secret, $signName = 'sign')
    {
        $sign = $paramArr[$signName];
        unset($paramArr[$signName]);

        ksort($paramArr);
        $signStr = '';
        foreach ($paramArr as $key => $val) {
            $signStr .= $key . '=' . urlencode($val) . '&';
        }

        // 去掉最后一个&
        $signStr = substr($signStr, 0, strlen($signStr) - 1);

        // 排好序的参数加上secret,进行md5
        $signStr .= $secret;
        if (strtolower(md5($signStr)) !== $sign) {
            $message = '签名错误';
            YII_DEBUG && $message .= ':' . $signStr;

            throw new UnprocessableEntityHttpException($message);
        }

        return true;
    }
}