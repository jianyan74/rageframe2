<?php

namespace services\common;

use Yii;
use common\helpers\EchantsHelper;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\queues\LogJob;
use common\models\common\Log;
use common\models\api\AccessToken;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\enums\SubscriptionActionEnum;
use common\enums\SubscriptionReasonEnum;
use common\enums\MessageLevelEnum;

/**
 * Class LogService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class LogService extends Service
{
    /**
     * 丢进队列
     *
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * 状态码
     *
     * @var int
     */
    private $statusCode;

    /**
     * 状态内容
     *
     * @var string
     */
    private $statusText;

    /**
     * 报错详细数据
     *
     * @var array
     */
    private $errData = [];

    /**
     * 不记录的状态码
     *
     * @var array
     */
    public $exceptCode = [];

    /**
     * 日志记录
     *
     *
     * @param $response
     * @param bool $showReqId
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function record($response, $showReqId = false)
    {
        // 判断是否记录日志
        if (in_array($this->getLevel($response->statusCode), Yii::$app->params['user.log.level'])) {
            // 检查是否报错
            if ($response->statusCode >= 300 && $exception = Yii::$app->getErrorHandler()->exception) {
                $this->errData = [
                    'type' => get_class($exception),
                    'file' => method_exists($exception, 'getFile') ? $exception->getFile() : '',
                    'errorMessage' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'stack-trace' => explode("\n", $exception->getTraceAsString()),
                ];

                $showReqId && $response->data['req_id'] = Yii::$app->params['uuid'];
            }

            $this->statusCode = $response->statusCode;
            $this->statusText = $response->statusText;

            // 排除状态码
            if (Yii::$app->params['user.log'] && !in_array($this->statusCode,
                    ArrayHelper::merge($this->exceptCode, Yii::$app->params['user.log.except.code']))) {
                $this->push();
            }
        }

        return $this->errData;
    }

    /**
     * 推入日志
     *
     * 判断进入队列或直接写入数据库
     */
    public function push()
    {
        try {
            // 判断是否开启队列
            if ($this->queueSwitch == true) {
                $message_id = Yii::$app->queue->push(new LogJob([
                    'data' => $this->getData(),
                ]));
            } else {
                $this->realCreate($this->getData());
            }
        } catch (\Exception $e) {

        }
    }

    /**
     * 真实写入
     *
     * @param $data
     */
    public function realCreate($data)
    {
        $log = new Log();
        $log->attributes = $data;
        $log->save();

        // 记录风控日志
        Yii::$app->services->reportLog->create($log);

        // 创建订阅消息
        $action = $this->getLevel($log['error_code']);
        $actions = [
            MessageLevelEnum::SUCCESS => SubscriptionActionEnum::LOG_SUCCESS,
            MessageLevelEnum::INFO => SubscriptionActionEnum::LOG_INFO,
            MessageLevelEnum::WARNING => SubscriptionActionEnum::LOG_WARNING,
            MessageLevelEnum::ERROR => SubscriptionActionEnum::LOG_ERROR,
        ];

        // 加入提醒池
        Yii::$app->services->backendNotify->createRemind(
            $log->id,
            SubscriptionReasonEnum::LOG_CREATE,
            $actions[$action],
            $log['user_id'],
            MessageLevelEnum::getValue($action) . "请求：" . $log->error_msg
        );
    }

    /**
     * @param int $code
     * @param string $text
     * @param $error_data
     */
    public function setErrorStatus(int $code, string $text, $error_data)
    {
        $this->statusCode = $code;
        $this->statusText = $text;
        $this->errData = $error_data;
    }

    /**
     * 状态报表统计
     *
     * @param $type
     * @return array
     */
    public function stat($type)
    {
        $fields = [];
        $codes = [400, 401, 403, 404, 405, 422, 429, 500];
        foreach ($codes as $code) {
            $fields[$code] = $code . '错误';
        }

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);

        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) use ($codes) {
            $statData = Log::find()
                ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count', 'error_code'])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['in', 'error_code', $codes])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->groupBy(['time', 'error_code'])
                ->asArray()
                ->all();

            return EchantsHelper::regroupTimeData($statData, 'error_code');
        }, $fields, $time, $format);
    }

    /**
     * 流量报表统计
     *
     * @param $type
     * @return array
     */
    public function flowStat($type)
    {
        $fields = [
            'count' => '访问量(PV)',
            'user_id' => '访问人数(UV)',
            'ip' => '访问IP',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);

        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return Log::find()
                ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count', 'count(distinct(ip)) as ip', 'count(distinct(user_id)) as user_id'])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function getData()
    {
        $data = $this->initData();
        $data['req_id'] = Yii::$app->params['uuid'];
        $data['error_code'] = $this->statusCode;
        $data['error_data'] = $this->errData;
        $data['error_msg'] = isset($this->errData['errorMessage']) ? $this->errData['errorMessage'] : $this->statusText;

        return $data;
    }

    /**
     * 初始化默认日志数据
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function initData()
    {
        $user_id = Yii::$app->user->id;
        if (Yii::$app->id == AppEnum::API && !Yii::$app->user->isGuest) {
            /** @var AccessToken $identity */
            $identity = Yii::$app->user->identity;
            $user_id = $identity->member_id ?? 0;
        }

        $url = explode('?', Yii::$app->request->getUrl());

        $data = [];
        $data['user_id'] = $user_id ?? 0;
        $data['app_id'] = Yii::$app->id;
        $data['merchant_id'] = Yii::$app->services->merchant->getId();
        $data['url'] = $url[0];
        $data['get_data'] = Yii::$app->request->get();
        $data['header_data'] = ArrayHelper::toArray(Yii::$app->request->headers);

        // 过滤敏感字段
        $post_data = Yii::$app->request->post();
        $noPostData = Yii::$app->params['user.log.noPostData'];
        foreach ($noPostData as $noPostDatum) {
            isset($post_data[$noPostDatum]) && $post_data[$noPostDatum] = '';
        }

        $data['post_data'] = $post_data;
        $data['user_agent'] = Yii::$app->debris->detectVersion();
        $data['method'] = Yii::$app->request->method;
        $data['module'] = Yii::$app->controller->module->id ?? '';
        $data['controller'] = Yii::$app->controller->id ?? '';
        $data['action'] = Yii::$app->controller->action->id ?? '';
        $data['ip'] = (int)ip2long(Yii::$app->request->userIP);
        $data['created_at'] = time();

        return $data;
    }

    /**
     * 获取报错级别
     *
     * @param $statusCode
     * @return bool|string
     */
    private function getLevel($statusCode)
    {
        if ($statusCode < 300) {
            return MessageLevelEnum::SUCCESS;
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            return MessageLevelEnum::INFO;
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            return MessageLevelEnum::WARNING;
        } elseif ($statusCode >= 500) {
            return MessageLevelEnum::ERROR;
        }

        return MessageLevelEnum::ERROR;
    }
}