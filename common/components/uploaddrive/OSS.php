<?php

namespace common\components\uploaddrive;

use Yii;
use yii\helpers\Json;
use common\helpers\Url;
use common\models\common\Attachment;
use Xxtime\Flysystem\Aliyun\OssAdapter;

/**
 * Class OSS
 * @package common\components\uploaddrive
 * @author jianyan74 <751393839@qq.com>
 */
class OSS extends DriveInterface
{
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
    public function config($maxSize, $path = '', $expire = 30, $type = Attachment::UPLOAD_TYPE_FILES, $callbackUrl = '')
    {
        $config = $this->config;

        $id = $config['storage_aliyun_accesskeyid'];
        $key = $config['storage_aliyun_accesskeysecret'];
        $bucket = $config['storage_aliyun_bucket'];
        $endpoint = $config['storage_aliyun_endpoint'];
        $host = "https://$bucket.$endpoint";
        // CNAME别名
        if (!empty($config['storage_aliyun_user_url'])) {
            $host = $config['storage_aliyun_transport_protocols'] . "://" . $config['storage_aliyun_user_url'];
        }

        !$callbackUrl && $callbackUrl = Url::toFront(['storage/oss'], true);
        $callback_param = [
            'callbackUrl' => $callbackUrl,
            'callbackBody' => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}&format=${imageInfo.format}&md5=${x:md5}&merchant_id=${x:merchant_id}&type=${x:type}&host=${x:host}&upload_id=${x:upload_id}',
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ];

        $base64_callback_body = base64_encode(Json::encode($callback_param));
        $expiration = $this->expiration(time() + $expire);
        // 最大文件大小
        $conditions[] = ['content-length-range', 0, $maxSize];

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        // $conditions[] = ['starts-with','$filename', $dir];

        $arr = [
            'expiration' => $expiration,
            'conditions' => $conditions
        ];

        $policy = Json::encode($arr);
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
            'x:merchant_id' => Yii::$app->services->merchant->getId(),
            'x:upload_id' => Yii::$app->request->userIP,
            'x:type' => $type,
            'x:host' => $host,
        ];
    }

    /**
     * 截止日期
     *
     * @param $time
     * @return string
     * @throws \Exception
     */
    protected function expiration($time)
    {
        $dtStr = date("c", $time);
        $datatime = new \DateTime($dtStr);
        $expiration = $datatime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);

        return $expiration . "Z";
    }

    /**
     * @param $baseInfo
     * @param $fullPath
     * @return mixed
     */
    protected function baseUrl($baseInfo, $fullPath)
    {
        $user_url = $this->config['storage_aliyun_user_url'];
        if (!empty($user_url)) {
            $baseInfo['url'] = $this->config['storage_aliyun_transport_protocols'] . '://' . $user_url . '/' . $baseInfo['url'];
        } else {
            $raw = $this->adapter->supports->getFlashData();
            $baseInfo['url'] = $raw['info']['url'];
        }

        return $baseInfo;
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    protected function create()
    {
        $this->adapter = new OssAdapter([
            'accessId' => $this->config['storage_aliyun_accesskeyid'],
            'accessSecret' => $this->config['storage_aliyun_accesskeysecret'],
            'bucket' => $this->config['storage_aliyun_bucket'],
            'endpoint' => $this->config['storage_aliyun_is_internal'] == true ? $this->config['storage_aliyun_endpoint_internal'] : $this->config['storage_aliyun_endpoint'],
            // 'timeout' => 3600,
            // 'connectTimeout' => 10,
            // 'isCName' => false,
            // 'token' => '',
        ]);
    }
}