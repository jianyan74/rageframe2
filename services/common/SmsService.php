<?php
namespace services\common;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use yii\helpers\Json;
use common\queues\SmsJob;
use common\components\Service;
use common\models\common\SmsLog;
use common\helpers\ArrayHelper;
use Overtrue\EasySms\EasySms;

/**
 * Class SmsService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class SmsService extends Service
{
    /**
     * 消息队列
     *
     * @var bool
     */
    public $queueSwitch = false;

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
                    'access_key_id' => Yii::$app->debris->config('sms_aliyun_accesskeyid'),
                    'access_key_secret' => Yii::$app->debris->config('sms_aliyun_accesskeysecret'),
                    'sign_name' => Yii::$app->debris->config('sms_aliyun_sign_name'),
                ]
            ],
        ];
    }

    /**
     * 发送短信
     *
     * ```php
     *       Yii::$app->services->sms->send($mobile, $code, $member_id)
     * ```
     *
     * @param $mobile
     * @param $code
     * @param int $member_id
     * @throws UnprocessableEntityHttpException
     */
    public function send($mobile, $code, $usage, $member_id = 0)
    {
        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new SmsJob([
                'mobile' => $mobile,
                'code' => $code,
                'usage' => $usage,
                'member_id' => $member_id,
            ]));

            return $messageId;
        }

        return $this->realSend($mobile, $code, $usage, $member_id = 0);
    }

    /**
     * 真实发送短信
     *
     * @param $mobile
     * @param $code
     * @param int $member_id
     * @throws UnprocessableEntityHttpException
     */
    public function realSend($mobile, $code, $usage, $member_id = 0)
    {
        $template = Yii::$app->debris->config('sms_aliyun_template');
        !empty($template) && $template = ArrayHelper::map(unserialize($template), 'group', 'template');
        $templateID = $template[$usage] ?? '';

        try {
            // 校验发送是否频繁
            if (($smsLog = $this->findByMobile($mobile)) && $smsLog['created_at'] + 60 > time()) {
                throw new UnprocessableEntityHttpException('请不要频繁发送短信');
            }

            $easySms = new EasySms($this->config);
            $result = $easySms->send($mobile, [
                'template' => $templateID,
                'data' => [
                    'code' => $code,
                ],
            ]);

            $this->saveLog([
                'mobile' => $mobile,
                'code' => (string) $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'error_code' => 200,
                'error_msg' => 'ok',
                'error_data' => Json::encode($result),
            ]);
        } catch (\Exception $e) {
            $this->saveLog([
                'mobile' => $mobile,
                'code' => (string) $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'error_code' => 422,
                'error_msg' => '发送失败',
                'error_data' => $e->getMessage(),
            ]);

            throw new UnprocessableEntityHttpException('短信发送失败');
        }
    }

    /**
     * @param $mobile
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMobile($mobile)
    {
        return SmsLog::find()
            ->where(['mobile' => $mobile])
            ->orderBy('id desc')
            ->asArray()
            ->one();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function saveLog($data = [])
    {
        $log = new SmsLog();
        $log = $log->loadDefaultValues();
        $log->attributes = $data;
        return $log->save();
    }
}