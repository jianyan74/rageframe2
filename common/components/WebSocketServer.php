<?php

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use Swoole\WebSocket\Server;
use common\helpers\ArrayHelper;
use common\websockets\SiteWebSocket;

/**
 * Class WebSocketServer
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class WebSocketServer
{
    protected $host;
    protected $port;
    protected $mode;
    protected $socket_type;
    protected $type;
    protected $config;

    /**
     * 子服务
     *
     * @var
     */
    public $childService = [
        'site' => SiteWebSocket::class,
    ];

    protected $_childService;

    /**
     * 服务
     *
     * @var Server
     */
    protected $server;

    /**
     * 连接id
     *
     * @var
     */
    protected $fd;

    /**
     * WebSocket constructor.
     * @param $host
     * @param $port
     * @param $config
     */
    public function __construct($host, $port, $mode, $socket_type, $type, $config)
    {
        $this->host = $host;
        $this->port = $port;
        $this->mode = $mode;
        $this->socket_type = $socket_type;
        $this->type = $type;
        $this->config = $config;
    }

    /**
     * 启动进程
     */
    public function run()
    {
        if ($this->type == 'wss') {
            $this->server = new Server($this->host, $this->port, $this->mode, $this->socket_type | SWOOLE_SSL);
        } else {
            $this->server = new Server($this->host, $this->port, $this->mode);
        }

        $this->server->set($this->config);
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('close', [$this, 'onClose']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->start();
    }

    /**
     * 开启连接
     *
     * @param $server
     * @param $frame
     */
    public function onOpen(Server $server, $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";

        $this->fd = $request->fd;
        // 验证 token

        $server->push($request->fd, $this->json(2001, '连接成功', ['fd' => $request->fd]));
    }

    /**
     * 消息
     * @param $server
     * @param $frame
     * @throws \Exception
     */
    public function onMessage(Server $server, $frame)
    {
        $this->fd = $frame->fd;
        try {
            // 消息
            $data = Json::decode($frame->data);
            $runAction = explode('.', $data['route']);
            $content = $data['content'];
            // 对应方法
            $action = 'action' . ucfirst(strtolower($runAction[1]));
            $this->childService($runAction[0], $server, $frame, $content)->$action();
        } catch (\Exception $e) {
            $server->push($frame->fd, $this->json(5000, "出现了报错：" . $e->getMessage()));
        }

        echo "receive from {}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    }

    /**
     * 接收http触发所有websocket的推送
     *
     * @param \Swoole\Http\Request $request
     * @param \Swoole\Http\Response $response
     */
    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        echo "server: http handshake success with fd{$request->fd}\n";

        // 接收http请求从get获取message参数的值，给用户推送
        // $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
        foreach ($this->server->connections as $fd) {
            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
            if ($this->server->isEstablished($fd)) {
                $this->server->push($fd, $this->json(2000, 'ok', $request->get['message']));
            }
        }
    }

    /**
     * 关闭连接
     *
     * @param $server
     * @param $fd
     */
    public function onClose(Server $server, $fd)
    {
        $this->fd = $fd;
        if ($server->isEstablished($fd)) {
            $server->push($fd, $this->json(4000, '长时间未检测到心跳,已被强行断开连接！', ['fd' => $fd]));
        }

        echo "client {$fd} closed" . PHP_EOL;
    }

    /**
     * 处理异步任务
     *
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public function onTask(Server $server, $task_id, $from_id, $data)
    {
        $this->fd = $from_id;

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
    public function onFinish(Server $server, $task_id, $data)
    {
        echo "AsyncTask[$task_id] 完成: $data" . PHP_EOL;
    }

    /**
     * 获取 services 里面配置的子服务 childService 的实例
     *
     * @param $childServiceName
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function childService($childServiceName, $server, $frame, $content)
    {
        if (!isset($this->_childService[$childServiceName])) {
            $childService = $this->childService;

            if (isset($childService[$childServiceName])) {
                $service = $childService[$childServiceName];
                $this->_childService[$childServiceName] = Yii::createObject($service);
            } else {
                throw new InvalidConfigException('Child Service [' . $childServiceName . '] is not find in ' . get_called_class() . ', you must config it! ');
            }
        }

        $this->_childService[$childServiceName]->server = $server;
        $this->_childService[$childServiceName]->frame = $frame;
        $this->_childService[$childServiceName]->content = $content;

        return $this->_childService[$childServiceName];
    }

    /**
     * 返回json数据格式
     *
     * @param int $code 状态码
     * @param string $message 返回的报错信息
     * @param array|object $data 返回的数据结构
     */
    protected function json($code = 4003, $message = '未知错误', $data = [])
    {
        $result = [
            'code' => strval($code),
            'message' => trim($message),
            'data' => $data ? ArrayHelper::toArray($data) : [],
        ];

        return Json::encode($result);
    }
}