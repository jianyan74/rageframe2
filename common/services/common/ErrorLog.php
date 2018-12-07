<?php
namespace common\services\common;

use Yii;
use common\queues\LogJob;
use common\helpers\StringHelper;
use common\services\Service;
use common\models\common\Log;

/**
 * Class ErrorLog
 * @package common\services\common
 */
class ErrorLog extends Service
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
     * @var
     */
    private $statusCode;

    /**
     * 状态内容
     *
     * @var
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
     * @var
     */
    private $req_id;

    /**
     * 日志记录
     *
     * 注意：可以考虑丢入到队列去执行
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
        if (Yii::$app->params['user.log'] && in_array($this->getLevel($response->statusCode), Yii::$app->params['user.log.level']))
        {
            $req_id = StringHelper::uuid('uniqid');
            // 检查是否报错
            if ($response->statusCode >= 300 && $exception = Yii::$app->getErrorHandler()->exception)
            {
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

            $this->insertLog();
        }

        return $this->errData;
    }

    /**
     * 写入日志
     *
     * @throws \yii\base\InvalidConfigException
     */
    private function insertLog()
    {
        // 判断是否开启队列
        if ($this->queueSwitch == true)
        {
            $messageId = Yii::$app->queue->push(new LogJob([
                'data' => $this->getData(),
            ]));
        }
        else
        {
            Log::record($this->getData());
        }
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function getData()
    {
        $member_id = Yii::$app->user->id;
        $url = explode('?', Yii::$app->request->getUrl());

        $data = [];
        $data['member_id'] = $member_id ?? 0;
        $data['url'] = $url[0];
        $data['get_data'] = json_encode(Yii::$app->request->get());

        $module = $controller = $action = '';
        isset(Yii::$app->controller->module->id) && $module = Yii::$app->controller->module->id;
        isset(Yii::$app->controller->id) && $controller = Yii::$app->controller->id;
        isset(Yii::$app->controller->action->id) && $action = Yii::$app->controller->action->id;

        $route = $module . '/' . $controller . '/' . $action;
        if (!in_array($route, Yii::$app->params['user.log.noPostData']))
        {
            $data['post_data'] = json_encode(Yii::$app->request->post());
        }

        $data['method'] = Yii::$app->request->method;
        $data['module'] = $module;
        $data['controller'] = $controller;
        $data['action'] = $action;
        $data['ip'] = ip2long(Yii::$app->request->userIP);
        $data['req_id'] = $this->req_id;
        $data['error_code'] = $this->statusCode;
        $data['error_msg'] = $this->statusText;
        $data['error_data'] = json_encode($this->errData);

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
        if ($statusCode < 300)
        {
            return 'info';
        }

        if ($statusCode >= 300 && $statusCode < 500)
        {
            return 'warning';
        }

        if ($statusCode >= 500)
        {
            return 'error';
        }

        return false;
    }
}