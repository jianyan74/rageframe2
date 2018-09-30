<?php
namespace api\modules\v1\models;

use Yii;
use yii\base\Model;
use aliyun\live\Client;
use common\helpers\StringHelper;

/**
 * Class AliYunLive
 * @package api\modules\v1\models
 */
class AliYunLive extends Model
{
    /**
     * @param $event
     * @throws \Exception
     */
    public static function create($event)
    {
        $model = $event->room;

        // 创建阿里直播SDK实例
        $config = Yii::$app->debris->configAll();
        $live = new Client([
            'accessKeyId' => $config['aliyun_live_access_key_id'],
            'accessSecret' => $config['aliyun_live_access_secret'],
            'appName' => 'live',
            'domain' => $config['aliyun_live_domain'],
            'recordDomain' => $config['aliyun_live_domain'],
            'pushAuth' => $config['aliyun_live_push_auth'],
        ]);

        //发送接口请求
        $package = [
            'Action' => 'DescribeLiveStreamsPublishList',
            'DomainName' => $config['aliyun_live_domain'],
            'StartTime' => gmdate('Y-m-d\TH:i:s\Z', $model->start_time),
            'EndTime' => gmdate('Y-m-d\TH:i:s\Z', $model->end_time),
        ];

        $response = $live->createRequest($package);
        $uuid = StringHelper::uuid('uniqid');
        //获取播放地址
        $playUrl = $live->getPlayUrls($uuid);
        $liveData = [
            'push_path' => $live->getPushPath(),// 推流根地址
            'push_path_arg' => $live->getPushArg($uuid),// 推流变量
            'pull_path_rtmp' => $playUrl['rtmp'],
            'pull_path_flv' => $playUrl['flv'],
            'pull_path_m3u8' => $playUrl['m3u8'],
        ];

        // 直播地址写入
        $model->attributes = $liveData;
        $model->save();
    }
}
