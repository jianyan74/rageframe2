<?php

namespace common\components;

use common\helpers\RegularHelper;
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
    public function __construct($drive, $superaddition = false)
    {
        $this->drive = $drive;
        $config = Yii::$app->debris->configAll();

        switch ($this->drive) {
            // 本地
            case Attachment::DRIVE_LOCAL :
                if ($superaddition !== false) {
                    $this->adapter = new Local(Yii::getAlias('@attachment'), FILE_APPEND);
                } else {
                    $this->adapter = new Local(Yii::getAlias('@attachment'));
                }
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
                if ($fullPath == true && !RegularHelper::verify('url', $baseInfo['url'])) {
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

        if ($this->drive != Attachment::DRIVE_LOCAL && !RegularHelper::verify('url', $baseInfo['url'])) {
            $baseInfo['url'] = 'http://' . $baseInfo['url'];
        }

        return $baseInfo;
    }

    /**
     * 获取阿里云js直传
     *
     * @param $maxSize
     * @param string dir 用户上传文件时指定的前缀
     * @param int $expire 设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
     * @param string $callbackUrl 为上传回调服务器的URL，请将下面的IP和Port配置为您自己的真实URL信息
     * @return array
     * @throws \Exception
     */
    public static function getOssJsConfig($maxSize, $path = '', $expire = 30, $callbackUrl = '')
    {
        $config = Yii::$app->debris->configAll();

        $id = $config['storage_aliyun_accesskeyid'];
        $key = $config['storage_aliyun_accesskeysecret'];
        $bucket = $config['storage_aliyun_bucket'];
        $endpoint = $config['storage_aliyun_endpoint'];
        $host = "http://$bucket.$endpoint";

        $callback_param = [
            'callbackUrl' => $callbackUrl,
            'callbackBody' => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ];

        $base64_callback_body = base64_encode(json_encode($callback_param));
        $expiration = self::getExpiration(time() + $expire);
        // 最大文件大小
        $conditions[] = ['content-length-range', 0, $maxSize];

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
       // $conditions[] = ['starts-with','$filename', $dir];

        $arr = [
            'expiration' => $expiration,
            'conditions' => $conditions
        ];

        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $signature = base64_encode(hash_hmac('sha1', $base64_policy, $key, true));

        return [
            'Filename' => '${filename}',
            'key' => $path . '${filename}',
            'OSSAccessKeyId' => $id,
            'success_action_status' => '201',
            'host' => $host,
            'policy' => $base64_policy,
            'signature' => $signature,
            'callback' => $base64_callback_body,
        ];
    }

    /**
     * @param $time
     * @return string
     * @throws \Exception
     */
    protected static function getExpiration($time)
    {
        $dtStr = date("c", $time);
        $datatime = new \DateTime($dtStr);
        $expiration = $datatime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);

        return $expiration . "Z";
    }
}