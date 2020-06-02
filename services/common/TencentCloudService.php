<?php

namespace services\common;

use common\components\Service;
use TencentCloud\Captcha\V20190722\CaptchaClient;
use TencentCloud\Captcha\V20190722\Models\DescribeCaptchaResultRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;

/**
 * 腾讯云接口
 *
 * Class TencentCloudService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class TencentCloudService extends Service
{
    /**
     * @var Credential
     */
    protected $cred;

    public function init()
    {
        parent::init();

        $this->cred = new Credential("secretId", "secretKey");
    }

    /**
     * 007验证码校验
     */
    public function captcha()
    {
        try {
            // # 实例化要请求产品(以cvm为例)的client对象
            $client = new CaptchaClient($this->cred, "ap-guangzhou");
            // 实例化一个请求对象
            $req = new DescribeCaptchaResultRequest();
            // 通过client对象调用想要访问的接口，需要传入请求对象
            $resp = $client->DescribeCaptchaResult($req);

            print_r($resp->toJsonString());
        } catch(TencentCloudSDKException $e) {
            echo $e;
        }
    }
}