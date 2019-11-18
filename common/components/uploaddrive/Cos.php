<?php

namespace common\components\uploaddrive;

use Overtrue\Flysystem\Cos\CosAdapter;

/**
 * Class Cos
 * @package common\components\uploaddrive
 * @author jianyan74 <751393839@qq.com>
 */
class Cos extends DriveInterface
{
    /**
     * @param $baseInfo
     * @param $fullPath
     * @return mixed
     */
    protected function baseUrl($baseInfo, $fullPath)
    {
        $config = $this->config;

        if (empty($config['read_from_cdn'])) {
            $bucket = $config['storage_cos_bucket'] ?? '';
            $appid = $config['storage_cos_appid'] ?? '';
            $region = $config['storage_cos_region'] ?? '';
            $baseInfo['url'] = 'https://' . $bucket . '-' . $appid . '.cos.' . $region . '.myqcloud.com/' . $baseInfo['url'];
        } else {
            $baseInfo['url'] = $config['storage_cos_cdn'] . $baseInfo['url'];
        }

        return $baseInfo;
    }

    /**
     * @return mixed|void
     */
    protected function create()
    {
        $config = $this->config;

        $this->adapter = new CosAdapter([
            'region' => $config['storage_cos_region'], // 'ap-guangzhou'
            'credentials' => [
                'appId' => $config['storage_cos_appid'], // 域名中数字部分
                'secretId' => $config['storage_cos_accesskey'],
                'secretKey' => $config['storage_cos_secrectkey'],
            ],
            'bucket' => $config['storage_cos_bucket'],
            'timeout' => 60,
            'connect_timeout' => 60,
            'cdn' => $config['storage_cos_cdn'],
            'scheme' => 'https',
            'read_from_cdn' => !empty($config['read_from_cdn']),
        ]);
    }
}