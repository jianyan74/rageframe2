<?php

namespace services\common;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\helpers\Json;
use common\enums\StatusEnum;
use common\helpers\EchantsHelper;
use common\queues\SmsJob;
use common\components\Service;
use common\models\common\SmsLog;
use common\helpers\ArrayHelper;
use common\enums\MessageLevelEnum;
use common\enums\SubscriptionActionEnum;
use common\enums\SubscriptionReasonEnum;
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
                    'access_key_id' => Yii::$app->debris->backendConfig('sms_aliyun_accesskeyid'),
                    'access_key_secret' => Yii::$app->debris->backendConfig('sms_aliyun_accesskeysecret'),
                    'sign_name' => Yii::$app->debris->backendConfig('sms_aliyun_sign_name'),
                ]
            ],
        ];
    }

    /**
     * 发送短信
     *
     * ```php
     *       Yii::$app->services->sms->send($mobile, $code, $usage, $member_id)
     * ```
     *
     * @param int $mobile 手机号码
     * @param int $code 验证码
     * @param string $usage 用途
     * @param int $member_id 用户ID
     * @return string|null
     * @throws UnprocessableEntityHttpException
     */
    public function send($mobile, $code, $usage, $member_id = 0)
    {
        $ip = ip2long(Yii::$app->request->userIP);
        if ($this->queueSwitch == true) {
            $messageId = Yii::$app->queue->push(new SmsJob([
                'mobile' => $mobile,
                'code' => $code,
                'usage' => $usage,
                'member_id' => $member_id,
                'ip' => $ip
            ]));

            return $messageId;
        }

        return $this->realSend($mobile, $code, $usage, $member_id = 0, $ip);
    }

    /**
     * 真实发送短信
     *
     * @param $mobile
     * @param $code
     * @param $usage
     * @param int $member_id
     * @throws UnprocessableEntityHttpException
     */
    public function realSend($mobile, $code, $usage, $member_id = 0, $ip = 0)
    {
        $template = Yii::$app->debris->backendConfig('sms_aliyun_template');
        !empty($template) && $template = ArrayHelper::map(Json::decode($template), 'group', 'template');
        $templateID = $template[$usage] ?? '';

        try {
            // 校验发送是否频繁
            if (($smsLog = $this->findByMobile($mobile)) && $smsLog['created_at'] + 60 > time()) {
                throw new NotFoundHttpException('请不要频繁发送短信');
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
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 200,
                'error_msg' => 'ok',
                'error_data' => Json::encode($result),
            ]);
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $errorMessage = [];
            $exceptions = $e->getExceptions();
            $gateways = $this->config['default']['gateways'];

            foreach ($gateways as $gateway) {
                if (isset($exceptions[$gateway])) {
                    $errorMessage[$gateway] = $exceptions[$gateway]->getMessage();
                }
            }

            $log = $this->saveLog([
                'mobile' => $mobile,
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 422,
                'error_msg' => '发送失败',
                'error_data' => Json::encode($errorMessage),
            ]);

            // 加入提醒池
            Yii::$app->services->backendNotify->createRemind(
                $log->id,
                SubscriptionReasonEnum::SMS_CREATE,
                SubscriptionActionEnum::SMS_ERROR,
                $log['member_id'],
                MessageLevelEnum::getValue(MessageLevelEnum::ERROR) . "短信：$log->error_data"
            );

            throw new UnprocessableEntityHttpException('短信发送失败');
        }
    }

    /**
     * @param $type
     * @return array
     */
    public function stat($type)
    {
        $fields = [
            'count' => '异常发送数量'
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return SmsLog::find()
                ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count'])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['>', 'error_code', 399])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
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
     * @return SmsLog
     */
    protected function saveLog($data = [])
    {
        $log = new SmsLog();
        $log = $log->loadDefaultValues();
        $log->attributes = $data;
        $log->save();

        return $log;
    }
}