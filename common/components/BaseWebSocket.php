<?php

namespace common\components;

use yii\helpers\Json;
use common\helpers\ArrayHelper;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * websocket 基类
 *
 * Class BaseWebSocket
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class BaseWebSocket
{
    /**
     * code 状态码说明
     *
     * @var array
     */
    private $codeExplain = [
        2000 => '返回成功',
        2001 => '连接成功',
        2002 => '心跳成功',
        // 4000 以上为正常报错
        4000 => '客户端未响应连接关闭',
        4001 => '用户验证失败',
        4002 => '正常报错提示',
        // 5000 以上为服务器错误
        5000 => '服务器错误',
    ];

    /**
     * @var Server
     */
    public $server;

    /**
     * @var Frame
     */
    public $frame;

    /**
     * @var string|array
     */
    public $content;

    /**
     * 返回json数据格式
     *
     * @param int $code 状态码
     * @param string $message 返回的报错信息
     * @param array|object $data 返回的数据结构
     */
    public function json($code = 4003, $message = '未知错误', $data = [])
    {
        $result = [
            'code' => strval($code),
            'message' => trim($message),
            'data' => $data ? ArrayHelper::toArray($data) : [],
        ];

        return Json::encode($result);
    }

    public function isGuest()
    {

    }

    /**
     * 获取当前的用户信息
     *
     * @return array
     */
    public function getMember()
    {
        return [];
    }
}