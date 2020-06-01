<?php

namespace common\components\uploaddrive;

use Yii;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Overtrue\Flysystem\Qiniu\Plugins\FileUrl;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;

/**
 * Class Qiniu
 * @package common\components\uploaddrive
 * @author jianyan74 <751393839@qq.com>
 */
class Qiniu extends DriveInterface
{
    /**
     * @var string[]
     */
    protected $host_area = [
        1 => 'upload.qiniup.com', // 华东
        2 => 'upload-z1.qiniup.com', // 华北区
        3 => 'upload-z2.qiniup.com', // 华南区
        4 => 'upload-na0.qiniup.com', // 北美区
        5 => 'upload-as0.qiniup.com', // 东南亚区
    ];

    /**
     * 直传 token
     *
     * @return array
     */
    public function config()
    {
        $config = $this->config;

        $accessKey = $config['storage_qiniu_accesskey'];
        $secretKey = $config['storage_qiniu_secrectkey'];
        $host = $config['storage_qiniu_host'];
        $auth = new Auth($accessKey, $secretKey);
        $bucket = $config['storage_qiniu_bucket'];
        // 生成上传Token
        $token = $auth->uploadToken($bucket);

        return [
            'token' => $token,
            'x:merchant_id' => Yii::$app->services->merchant->getId(),
            'x:upload_id' => Yii::$app->request->userIP,
            'x:host' => $this->host_area[$host],
        ];
    }

    /**
     * @param $baseInfo
     * @param $fullPath
     */
    protected function baseUrl($baseInfo, $fullPath)
    {
        $this->filesystem->addPlugin(new FileUrl());
        $baseInfo['url'] = $this->filesystem->getUrl($baseInfo['url']);

        return $baseInfo;
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    protected function create()
    {
        $config = $this->config;

        $accessKey = $config['storage_qiniu_accesskey'];
        $secretKey = $config['storage_qiniu_secrectkey'];
        $cdnHost = $config['storage_qiniu_domain'];
        $bucket = $config['storage_qiniu_bucket'];
        $this->adapter = new QiniuAdapter($accessKey, $secretKey, $bucket, $cdnHost);
    }
}