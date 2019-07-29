<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use common\enums\StatusEnum;
use common\helpers\EchantsHelper;
use common\enums\AuthEnum;
use common\helpers\StringHelper;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\models\common\Log;
use common\queues\LogJob;
use common\models\api\AccessToken;

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
     * 唯一标识
     *
     * @var string
     */
    private $req_id;

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
        if (Yii::$app->params['user.log'] && in_array($this->getLevel($response->statusCode), Yii::$app->params['user.log.level'])) {
            $req_id = StringHelper::uuid('uniqid');

            // 检查是否报错
            if ($response->statusCode >= 300 && $exception = Yii::$app->getErrorHandler()->exception) {
                $this->errData = [
                    'type' => get_class($exception),
                    'file' => method_exists($exception, 'getFile') ? $exception->getFile() : '',
                    'errorMessage' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'stack-trace' => explode("\n", $exception->getTraceAsString()),
                ];

                $showReqId && $response->data['req_id'] = $req_id;
            }

            $this->statusCode = $response->statusCode;
            $this->statusText = $response->statusText;
            $this->req_id = $req_id;

            // 排除状态码
            !in_array($this->statusCode, ArrayHelper::merge($this->exceptCode, Yii::$app->params['user.log.except.code'])) && $this->insertLog();
        }

        return $this->errData;
    }

    /**
     * 写入日志
     */
    public function insertLog()
    {
        try {
            // 判断是否开启队列
            if ($this->queueSwitch == true) {
                $messageId = Yii::$app->queue->push(new LogJob([
                    'data' => $this->getData(),
                ]));
            } else {
                $log = new Log();
                $log->attributes = $this->getData();
                $log->save();
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * 初始化默认日志数据
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function initData()
    {
        $user_id = Yii::$app->user->id;
        if (Yii::$app->id == AuthEnum::TYPE_API && !Yii::$app->user->isGuest) {
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
        $data['get_data'] = Json::encode(Yii::$app->request->get());
        $data['header_data'] = Json::encode(ArrayHelper::toArray(Yii::$app->request->headers));

        $module = $controller = $action = '';
        isset(Yii::$app->controller->module->id) && $module = Yii::$app->controller->module->id;
        isset(Yii::$app->controller->id) && $controller = Yii::$app->controller->id;
        isset(Yii::$app->controller->action->id) && $action = Yii::$app->controller->action->id;

        $route = $module . '/' . $controller . '/' . $action;
        if (!in_array($route, Yii::$app->params['user.log.noPostData'])) {
            $data['post_data'] = Json::encode(Yii::$app->request->post());
        }

        $data['device'] = Yii::$app->debris->detectVersion();
        $data['method'] = Yii::$app->request->method;
        $data['module'] = $module;
        $data['controller'] = $controller;
        $data['action'] = $action;
        $data['ip'] = ip2long(Yii::$app->request->userIP);

        return $data;
    }

    /**
     * @param int $code
     */
    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
    }

    /**
     * @param string $text
     */
    public function setStatusText(string $text)
    {
        $this->statusText = $text;
    }

    /**
     * @param $error_data
     */
    public function setErrData($error_data)
    {
        $this->errData = $error_data;
    }

    /**
     * 报表统计
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
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function getData()
    {
        $data = $this->initData();
        $data['req_id'] = $this->req_id;
        $data['error_code'] = $this->statusCode;
        $data['error_msg'] = $this->statusText;
        $data['error_data'] = is_array($this->errData) ? Json::encode($this->errData) : $this->errData;

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
            return 'info';
        }

        if ($statusCode >= 300 && $statusCode < 400) {
            return 'warning';
        }

        if ($statusCode >= 400) {
            return 'error';
        }

        return false;
    }
}