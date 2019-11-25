<?php

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use Swoole\WebSocket\Server;

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
        'test' => BaseWebSocket::class,
    ];

    protected $_childService;

    /**
     * 服务
     *
     * @var Server
     */
    protected $server;

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
    }

    /**
     * 消息
     * @param $server
     * @param $frame
     * @throws \Exception
     */
    public function onMessage(Server $server, $frame)
    {
        // 消息
        $data = Json::decode($frame->data);
        $runAction = explode('/', $data['action']);
        $content = $data['content'];

        try {
            // 对应方法
            $action = 'action' . ucfirst(strtolower($runAction[1]));
            $this->childService($runAction[0], $server, $frame, $content)->$action();
        } catch (\Exception $e) {
            $server->push($frame->fd, "出现了报错：" . $e->getMessage());
        }

        echo "receive from {}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    }

    /**
     * 关闭连接
     *
     * @param $server
     * @param $fd
     */
    public function onClose(Server $server, $fd)
    {
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
}