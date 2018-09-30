<?php
namespace common\helpers;

use Yii;
use yii\web\Response;

/**
 * ajax数据格式返回
 *
 * Class ResultDataHelper
 * @package common\helpers
 */
class ResultDataHelper
{
    /**
     * 返回json数据格式
     *
     * @param int $code 状态码
     * @param string $message 返回的报错信息
     * @param array|object $data 返回的数据结构
     */
    public static function result($code = 404, $message = '未知错误', $data = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = [
            'code' => strval($code),
            'message' => trim($message),
            'data' => $data ? ArrayHelper::toArray($data) : [],
        ];

        return $result;
    }

    /**
     * 返回json数据格式
     *
     * @param int $code 状态码 注意：要符合http状态码
     * @param string $message 返回的报错信息
     * @param array|object $data 返回的数据结构
     */
    public static function apiResult($code = 404, $message = '未知错误', $data = [])
    {
        Yii::$app->response->setStatusCode($code, $message);
        Yii::$app->response->data = $data ? ArrayHelper::toArray($data) : [];

        return Yii::$app->response->data;
    }
}