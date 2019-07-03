<?php

namespace common\components;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\common\Attachment;
use League\Flysystem\Adapter\Local;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use Overtrue\Flysystem\Qiniu\Plugins\FileUrl;
use Overtrue\Flysystem\Cos\CosAdapter;
use Xxtime\Flysystem\Aliyun\OssAdapter;
use League\Flysystem\Filesystem;

/**
 * Class UploadDrive
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class UploadDrive
{
    /**
     * @var string
     */
    protected $drive;

    /**
     * @var CosAdapter
     */
    protected $adapter;

    /**
     * 上传组件
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Filesystem constructor.
     * @param $drive
     * @throws \Exception
     */
    public function __construct($drive)
    {
        $this->drive = $drive;
        $config = Yii::$app->debris->configAll();
        
        switch ($this->drive) {
            // 本地
            case Attachment::DRIVE_LOCAL :
                $this->adapter = new Local(Yii::getAlias('@attachment'), FILE_APPEND);
                break;
            // 阿里云
            case Attachment::DRIVE_OSS :
                $this->adapter = new OssAdapter([
                    'accessId' => $config['storage_aliyun_accesskeyid'],
                    'accessSecret' => $config['storage_aliyun_accesskeysecret'],
                    'bucket' => $config['storage_aliyun_bucket'],
                    'endpoint' => $config['storage_aliyun_is_internal'] == true ? $config['storage_aliyun_endpoint_internal'] : $config['storage_aliyun_endpoint'],
                    // 'timeout' => 3600,
                    // 'connectTimeout' => 10,
                    // 'isCName'        => false,
                    // 'token'          => '',
                ]);
                break;
            // 七牛
            case Attachment::DRIVE_QINIU :
                $accessKey = $config['storage_qiniu_accesskey'];
                $secretKey = $config['storage_qiniu_secrectkey'];
                $cdnHost = $config['storage_qiniu_domain'];
                $bucket = $config['storage_qiniu_bucket'];
                $this->adapter = new QiniuAdapter($accessKey, $secretKey, $bucket, $cdnHost);
                break;
            // 腾讯Cos
            case Attachment::DRIVE_COS :
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
                break;
            default :
                throw new NotFoundHttpException('找不到上传驱动');
                break;
        }
    }

    /**
     * @return Filesystem
     */
    public function getEntity()
    {
        if (!$this->filesystem instanceof Filesystem) {
            $this->filesystem = new Filesystem($this->adapter);
        }

        return $this->filesystem;
    }

    /**
     * @param $baseInfo
     * @return mixed
     */
    public function getUrl($baseInfo, $fullPath)
    {
        switch ($this->drive) {
            // 本地
            case Attachment::DRIVE_LOCAL :
                $baseInfo['url'] = Yii::getAlias('@attachurl') . '/' . $baseInfo['url'];
                if ($fullPath == true) {
                    $baseInfo['url'] = Yii::$app->request->hostInfo . $baseInfo['url'];
                }
                break;
            // 阿里云
            case Attachment::DRIVE_OSS :

                $user_url = Yii::$app->debris->config('storage_aliyun_user_url');
                if (!empty($user_url)) {
                    $baseInfo['url'] = 'http://' . $user_url . '/' . $baseInfo['url'];
                } else {
                    $raw = $this->adapter->supports->getFlashData();
                    $baseInfo['url'] = $raw['info']['url'];
                }

                break;
            // 七牛
            case Attachment::DRIVE_QINIU :
                $this->filesystem->addPlugin(new FileUrl());
                $baseInfo['url'] = $this->filesystem->getUrl($baseInfo['url']);
                break;
            // 腾讯COS
            case Attachment::DRIVE_COS :
                if (empty($sysConfig['read_from_cdn'])) {
                    $bucket = Yii::$app->debris->config('storage_cos_bucket');
                    $appid = Yii::$app->debris->config('storage_cos_appid');
                    $region = Yii::$app->debris->config('storage_cos_region');
                    $baseInfo['url'] = 'https://' . $bucket . '-' . $appid . '.cos.' . $region . '.myqcloud.com/' . $baseInfo['url'];
                } else {
                    $baseInfo['url'] = $sysConfig['storage_cos_cdn'] . $baseInfo['url'];
                }
                break;
        }

        if ($this->drive != Attachment::DRIVE_LOCAL && !preg_match('/(http:\/\/)|(https:\/\/)/i', $baseInfo['url'])) {
            $baseInfo['url'] = 'http://' . $baseInfo['url'];
        }

        return $baseInfo;
    }
}