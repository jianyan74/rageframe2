<?php

namespace api\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class BeforeSend
 * @package api\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class BeforeSend extends Behavior
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
     * 格式化返回
     *
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

        // 记录日志
        $errData = Yii::$app->services->log->record($response, true);

        // 格式化报错输入格式
        if ($response->statusCode >= 500) {
            $response->data['data'] = YII_DEBUG ? $errData : '内部服务器错误,请联系管理员';
        }

        // 提取系统的报错信息
        if ($response->statusCode >= 300 && isset($response->data['data']['message']) && isset($response->data['data']['status'])) {
            $response->data['message'] = $response->data['data']['message'];
        }

        // ip黑名单
        if ($response->statusCode == 429) {
            Yii::$app->services->ipBlacklist->create(Yii::$app->request->userIP, '请求频率过高');
        }

        $response->format = yii\web\Response::FORMAT_JSON;
        $response->statusCode = 200; // 考虑到了某些前端必须返回成功操作，所以这里可以设置为都返回200的状态码
    }
}