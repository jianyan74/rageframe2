<?php

namespace common\components\uploaddrive;

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