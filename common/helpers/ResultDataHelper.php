<?php

namespace common\helpers;

use Yii;
use yii\web\Response;

/**
 * 格式化数据返回
 *
 * Class ResultDataHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
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
    public static function json($code = 404, $message = '未知错误', $data = [])
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
     * 返回 array 数据格式 api 自动转为 json
     *
     * @param int $code 状态码 注意：要符合http状态码
     * @param string $message 返回的报错信息
     * @param array|object $data 返回的数据结构
     */
    public static function api($code = 404, $message = '未知错误', $data = [])
    {
        Yii::$app->response->setStatusCode($code, $message);
        Yii::$app->response->data = $data ? ArrayHelper::toArray($data) : [];

        return Yii::$app->response->data;
    }
}