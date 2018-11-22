<?php
namespace common\services\common;

use Yii;
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
        $errData = [];
        if (Yii::$app->params['user.log'] && in_array($this->getLevel($response->statusCode), Yii::$app->params['user.log.level']))
        {
            $req_id = StringHelper::uuid('uniqid');
            // 检查是否报错
            if ($response->statusCode >= 300 && $exception = Yii::$app->getErrorHandler()->exception)
            {
                $errData = [
                    'type' => get_class($exception),
                    'file' => method_exists($exception, 'getFile') ? $exception->getFile() : '',
                    'errorMessage' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'stack-trace' => explode("\n", $exception->getTraceAsString()),
                ];

                $showReqId && $response->data['req_id'] = $req_id;
            }

            Log::record($response->statusCode, $response->statusText, $errData, $req_id);
        }

        return $errData;
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