<?php
namespace api\behaviors;

use Yii;
use yii\base\Behavior;
use common\models\api\Log;

/**
 * Class beforeSend
 * @package api\behaviors
 */
class beforeSend extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            'beforeSend' => 'beforeSend',
        ];
    }

    /**
     * @param $event
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSend($event)
    {
        $response = $event->sender;
        $response->data = [
            'code' => $response->statusCode,
            'message' => $response->statusText,
            'data' => $response->data,
        ];

        // 提取系统的报错信息
        if (isset($response->data['data']['message']) && isset($response->data['data']['status']))
        {
            $response->data['message'] = $response->data['data']['message'];
        }

        // 报错日志打印出来
        $responseData = [];
        if ($response->statusCode >= 300 && ($exception = Yii::$app->getErrorHandler()->exception))
        {
            $responseData = [
                'name' => ($exception instanceof \Exception || $exception instanceof \ErrorException) ? $exception->getName() : 'Exception',
                'type' => get_class($exception),
                'file' => method_exists($exception, 'getFile') ? $exception->getFile() : '',
                'errorMessage' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'stack-trace' => explode("\n", $exception->getTraceAsString()),
            ];

            if ($exception instanceof \Exception)
            {
                $responseData['error-info'] = $exception->errorInfo;
            }
        }

        // 格式化报错输入格式 默认为格式500状态码 其他可自行修改
        if ($response->statusCode == 500)
        {
            $response->data['data'] = '内部服务器错误';
            YII_DEBUG && $response->data['data'] = $responseData;
        }

        // 日志记录 可以考虑丢进队列去执行
        Yii::$app->params['user.log'] && Log::record($response->data['code'], $response->data['message'], $responseData);

        unset($responseData);
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->statusCode = 200; // 考虑到了某些前端必须返回成功操作，所以这里可以设置为都返回200的状态码
    }
}