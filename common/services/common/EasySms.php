<?php
namespace common\services\common;

use Yii;
use Overtrue\EasySms\EasySms as Sms;
use common\services\Service;
use common\models\common\SmsLog;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class Sms
 * @package common\services\common
 */
class EasySms extends Service
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
     * @throws UnprocessableEntityHttpException
     */
    public function send($mobile, $code)
    {
        $log = $this->getLogModel();

        try
        {
            $easySms = new Sms($this->config);
            $result = $easySms->send($mobile, [
                'template' => '',
                'data' => [
                    'code' => $code,
                ],
            ]);

            $log->save();
        }
        catch (\Exception $e)
        {
            $log->error_code = 422;
            $log->error_msg = $e->getMessage();
            $log->save();

            throw new UnprocessableEntityHttpException('短信发送失败');
        }
    }

    /**
     * @return SmsLog
     */
    private function getLogModel()
    {
        $log = new SmsLog();
        $log = $log->loadDefaultValues();
        $log->error_code = 200;
        $log->error_msg = '发送成功';

        return $log;
    }
}