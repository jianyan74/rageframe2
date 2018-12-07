<?php
namespace common\services\common;

use Yii;
use Overtrue\EasySms\EasySms;
use common\services\Service;
use common\models\common\SmsLog;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class Sms
 * @package common\services\common
 */
class Sms extends Service
{
    /**
     * @var array
     */
    protected $config = [];

    public function init()
    {
        parent::init();

        $this->config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => Yii::getAlias('runtime') . '/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => '',
                    'access_key_secret' => '',
                    'sign_name' => '',
                ]
            ],
        ];
    }

    /**
     * 发送短信
     *
     * @param $mobile
     * @param $code
     * @param int $member_id
     * @throws UnprocessableEntityHttpException
     */
    public function send($mobile, $code, $member_id = 0)
    {
        try
        {
            $easySms = new EasySms($this->config);
            $result = $easySms->send($mobile, [
                'template' => '',
                'data' => [
                    'code' => $code,
                ],
            ]);

            $this->saveLog([
                'mobile' => $mobile,
                'content' => $code,
                'member_id' => $member_id,
                'error_code' => 200,
                'error_msg' => 'ok',
                'error_data' => json_encode($result),
            ]);
        }
        catch (\Exception $e)
        {
            $this->saveLog([
                'mobile' => $mobile,
                'content' => $code,
                'member_id' => $member_id,
                'error_code' => 422,
                'error_msg' => '发送失败',
                'error_data' => $e->getMessage(),
            ]);

            throw new UnprocessableEntityHttpException('短信发送失败');
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function saveLog($data = [])
    {
        $log = new SmsLog();
        $log = $log->loadDefaultValues();
        $log->attributes = $data;
        return $log->save();
    }
}