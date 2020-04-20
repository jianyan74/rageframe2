<?php

namespace common\components;

use yii\helpers\Json;
use common\enums\StatusEnum;
use common\models\websocket\FdMemberMap;
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
        1006 => '意外断开',
        2000 => '返回成功',
        2001 => '连接成功',
        2002 => '心跳成功',
        2003 => '排队中',
        2004 => '排队成功',
        2005 => 'xxx客服为您服务',
        2006 => 'xxx用户来了',
        // 4000 以上为正常报错
        4000 => '客户端未响应连接关闭',
        4001 => '用户验证失败', // 直接踢下线
        4002 => '正常报错提示',
        4004 => '所请求的资源不存在',
        4101 => '已在别处登录', // 直接踢下线
        4102 => '用户已离线',
        4103 => '当前接待人数过多',
        4104 => '客服不在线',
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
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $route;

    /**
     * 参数
     *
     * @var array
     */
    public $params = [];

    /**
     * @var FdMemberMap|array
     */
    public $member;

    /**
     * 返回json数据格式
     *
     * @param int $code 状态码
     * @param string $message 返回的报错信息
     * @param array|object $data 返回的数据结构
     */
    public function json($code = 4003, $message = '未知错误', $data = [])
    {
        if (empty($data)) {
            $data = [];
        } elseif (is_array($data) || is_object($data)) {
            $data = ArrayHelper::toArray($data);
        }

        $result = [
            'code' => strval($code),
            'message' => trim($message),
            'data' => $data,
            'member' => [],
            'route' => $this->route,
            'timestamp' => time(),
        ];

        !empty($this->member) && $result['member'] = ArrayHelper::toArray($this->member);

        return Json::encode($result);
    }

    /**
     * 发送正常消息
     *
     * @param $content
     * @param $fd
     * @return bool|mixed
     */
    public function push($content, $fd = '', $code = 2000, $message = 'ok')
    {
        !$fd && $fd = $this->frame->fd;
        if ($this->server->isEstablished($fd)) {
            $this->server->push($fd, $this->json($code, $message, $content));
        }

        unset($content, $message, $code, $fd);

        return true;
    }

    /**
     * 发送正常消息
     *
     * @param $content
     * @param $fd
     * @return bool|mixed
     */
    public function pushError($message = 'ok', $code = 4002, $fd = '')
    {
        !$fd && $fd = $this->frame->fd;
        if ($this->server->isEstablished($fd)) {
            $this->server->push($fd, $this->json($code, $message));
        }

        unset($message, $code, $fd);

        return true;
    }

    /**
     * 关闭连接
     *
     * @param $fd
     * @param null $code
     * @param null $reason
     * @return bool
     */
    public function disconnect($fd, $code = null, $reason = null)
    {
        // 用户下线
        FdMemberMap::updateAll(['status' => StatusEnum::DISABLED], ['fd' => $fd]);
        // 踢下线
        if ($this->server->isEstablished($this->frame->fd)) {
            $this->server->disconnect($fd, $code, $reason);
        }

        unset($reason, $code, $fd);

        return true;
    }

    /**
     * @param $page
     * @return array
     */
    public function getPage($page)
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;

        return [$limit, $offset];
    }

    /**
     * 获取用户信息
     *
     * @param $member_id
     * @param $type
     * @param int $status
     * @return array|\yii\redis\ActiveRecord|null
     */
    public function getFdMemberMap($member_id, $type)
    {
        return FdMemberMap::find()->where([
            'member_id' => $member_id,
            'type' => $type,
        ])->one();
    }

    /**
     * 获取所有的在线
     *
     * @return array|\yii\redis\ActiveRecord[]
     */
    public function getAllServiceFdMemberMap()
    {
        return FdMemberMap::find()
            ->where([
            'type' => 'service',
            'status' => StatusEnum::ENABLED
        ])
            ->asArray()
            ->all();
    }
}