<?php
namespace jianyan\websocket\server;

use Yii;
use jianyan\websocket\live\Room;
use jianyan\websocket\live\RoomMap;
use jianyan\websocket\live\RoomMember;
use swoole_websocket_server;

/**
 * 长连接
 *
 * Class WebSocketServer
 * @package console\controllers
 */
class WebSocketServer
{
    protected $_host;

    protected $_port;

    protected $_mode;

    protected $_socketType;

    protected $_type;

    protected $_config;

    /**
     * 服务
     *
     * @var
     */
    protected $_server;

    /**
     * WebSocket constructor.
     * @param $host
     * @param $port
     * @param $config
     */
    public function __construct($host, $port, $mode, $socketType, $type, $config)
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_mode = $mode;
        $this->_socketType = $socketType;
        $this->_type = $type;
        $this->_config = $config;

        /************ 测试用可自行删除在别的地方引用 ***************/
        // 创建房间
        Room::set(10001);
        // 清理房间用户缓存
        RoomMember::release(10001);
        // 清理全部用户所在房间列表
        RoomMap::release();
        /************ 测试用可自行删除在别的地方引用 ***************/
    }

    /**
     * 启动进程
     */
    public function run()
    {
        if($this->_type == 'ws')
        {
            $this->_server = new swoole_websocket_server($this->_host, $this->_port, $this->_mode, $this->_socketType);
        }
        else
        {
            $this->_server = new swoole_websocket_server($this->_host, $this->_port, $this->_mode, $this->_socketType | SWOOLE_SSL);
        }

        $this->_server->set($this->_config);
        $this->_server->on('open', [$this, 'onOpen']);
        $this->_server->on('message', [$this, 'onMessage']);
        $this->_server->on('task', [$this, 'onTask']);
        $this->_server->on('finish', [$this, 'onFinish']);
        $this->_server->on('close', [$this, 'onClose']);
        $this->_server->start();
    }

    /**
     * 开启连接
     *
     * @param $server
     * @param $frame
     */
    public function onOpen($server, $frame)
    {
        echo "server: handshake success with fd{$frame->fd}\n";
        echo "server: {$frame->data}\n";

        // 验证token进行连接判断
    }

    /**
     * 消息
     * @param $server
     * @param $frame
     * @throws \Exception
     */
    public function onMessage($server, $frame)
    {
        if (!($message = json_decode($frame->data, true)))
        {
            echo "没有消息内容" . PHP_EOL;
            return true;
        }

        // 判断房间id
        if (!isset($message['room_id']) && in_array($message['type'], ['login']))
        {
            throw new \Exception("room_id not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$frame->data");
        }

        // 输出调试信息
        echo $frame->data . PHP_EOL;

        // 业务逻辑
        switch ($message['type'])
        {
            // 心跳
            case 'pong':

                return true;

                break;

            // 进入房间(登录)
            case 'login':

                $member = [
                    'fd' => $frame->fd,
                    'room_id' => $message['room_id'],
                    'user_id' => 1,
                    'nickname' => $message['nickname'],
                    'head_portrait' => $message['head_portrait'],
                ];

                // 加入全部列表
                RoomMap::set($frame->fd, $message['room_id']);
                // 加入房间列表
                RoomMember::set($message['room_id'], $frame->fd, $member);

                // 转发给自己获取在线列表
                $server->push($frame->fd, $this->singleMessage('list', $frame->fd, $frame->fd,[
                    'list' => RoomMember::list($message['room_id']),
                ]));

                // 转播给当前房间的所有客户端
                $server->task($this->massMessage($message['type'], $frame->fd, [
                    'count' => RoomMember::count($message['room_id']),
                    'member' => $member,
                ]));

                break;

            // 评论消息
            case 'say':

                // 私聊
                if($message['to_client_id'] != 'all')
                {
                    // 私发
                    $server->push($frame->fd, $this->singleMessage($message['type'], $frame->fd, $message['to_client_id'],[
                        'content' => nl2br(htmlspecialchars($message['content'])),
                    ]));

                    return true;
                }

                // 广播消息
                $server->task($this->massMessage($message['type'], $frame->fd, [
                    'content' => nl2br(htmlspecialchars($message['content'])),
                ]));

                break;

            // 礼物
            case 'gift':

                // 广播消息
                $server->task($this->massMessage($message['type'], $frame->fd, [
                    'gift_id' => $message['gift_id'],
                ]));

                break;
            // 离开房间
            case 'leave':

                if ($room_id = RoomMap::get($fd))
                {
                    // 删除
                    RoomMember::del($room_id, $fd);

                    // 推送退出房间
                    $server->task($this->massMessage($message['type'], $frame->fd, [
                        'count' => RoomMember::count($room_id),
                    ]));
                }

                break;
        }

        return true;
    }

    /**
     * 关闭连接
     *
     * @param $server
     * @param $fd
     */
    public function onClose($server, $fd)
    {
        echo "client {$fd} closed". PHP_EOL;

        // 验证是否进入房间，如果有退出房间列表
        if ($room_id = RoomMap::get($fd))
        {
            // 删除
            RoomMember::del($room_id, $fd);
            // 推送退出房间
            $server->task($this->massMessage('leave', $fd, [
                'count' => RoomMember::count($room_id),
            ]));
        }
    }

    /**
     * 处理异步任务
     *
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public function onTask($server, $task_id, $from_id, $data)
    {
        echo "新 AsyncTask[id=$task_id]" . PHP_EOL;

        $server->finish($data);
    }

    /**
     * 处理异步任务的结果
     *
     * @param $server
     * @param $task_id
     * @param $data
     */
    public function onFinish($server, $task_id, $data)
    {
        // 根据 fd 下发房间通知
        $sendData = json_decode($data, true);
        $room_id = RoomMap::get($sendData['from_client_id']);
        $list = RoomMember::list($room_id);
        unset($sendData, $room_id);

        //广播
        foreach ($list as $val)
        {
            $info = json_decode($val, true);
            $server->push($info['fd'], $data);

            unset($info);
        }

        echo "AsyncTask[$task_id] 完成: $data" . PHP_EOL;
    }

    /**
     * 群发消息
     *
     * @param $type
     * @param $from_client_id
     * @param array $otherArr
     * @return string
     */
    protected function massMessage($type, $from_client_id,array $otherArr = [])
    {
        $message = array_merge([
            'type' => $type,
            'from_client_id'=> $from_client_id,
            'to_client_id' => 'all',
            'time'=> date('Y-m-d H:i:s'),
        ], $otherArr);

        return json_encode($message);
    }

    /**
     * 单发消息
     *
     * @param $type
     * @param $from_client_id
     * @param $to_client_id
     * @param array $otherArr
     * @return string
     */
    protected function singleMessage($type, $from_client_id, $to_client_id,array $otherArr = [])
    {
        $message = array_merge([
            'type' => $type,
            'from_client_id'=> $from_client_id,
            'to_client_id' => $to_client_id,
            'time'=> date('Y-m-d H:i:s'),
        ], $otherArr);

        return json_encode($message);
    }
}