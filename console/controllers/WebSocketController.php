<?php

namespace console\controllers;

use yii\console\Controller;
use common\components\WebSocketServer;
use common\helpers\FileHelper;

/**
 *  WebSocket 启动方式
 *
 * 启动 php ./yii websocket/start
 * 停止 php ./yii websocket/stop
 * 重启 php ./yii websocket/restart
 *
 * Class WebSocketController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class WebSocketController extends Controller
{
    /**
     * 实例化服务
     *
     * @var WebSocketServer
     */
    public $server;

    /**
     * 监听地址
     *
     * @var string
     */
    public $host = '0.0.0.0';

    /**
     * 监听端口
     *
     * @var string
     */
    public $port = 9501;

    /**
     * 运行模式
     *
     * @var int
     */
    public $mode = SWOOLE_BASE;

    /**
     * 传输协议
     *
     * @var int
     */
    public $socket_type = SWOOLE_SOCK_TCP;

    /**
     * 长连接方式
     *
     * @var string
     */
    public $type = 'ws';

    /**
     * swoole 配置
     *
     * @var array
     */
    public $config = [
        'daemonize' => false, // 守护进程执行
        'task_worker_num' => 4,//task进程的数量
        'ssl_cert_file' => '',
        'ssl_key_file' => '',
        'pid_file' => '',
        'buffer_output_size' => 2 * 1024 * 1024, //配置发送输出缓存区内存尺寸
        'heartbeat_check_interval' => 60,// 心跳检测秒数
        'heartbeat_idle_time' => 600,// 检查最近一次发送数据的时间和当前时间的差，大于则强行关闭
    ];

    /**
     * 启动
     *
     * @throws \yii\base\Exception
     */
    public function actionStart()
    {
        if ($this->getPid() !== false) {
            $this->stderr("服务已经启动...");
            exit(1);
        }
        // 写入进程
        $this->setPid();
        /** @var WebSocketServer $websocket 运行 */
        $websocket = new $this->server(
            $this->host,
            $this->port,
            $this->mode,
            $this->socket_type,
            $this->type,
            $this->config
        );
        $websocket->run();

        $this->stdout("服务正在运行,监听 {$this->host}:{$this->port}" . PHP_EOL);
    }

    /**
     * 关闭进程
     */
    public function actionStop()
    {
        $this->sendSignal(SIGTERM);
        $this->stdout("服务已经停止, 停止监听 {$this->host}:{$this->port}" . PHP_EOL);
    }

    /**
     * 重启进程
     *
     * @throws \yii\base\Exception
     */
    public function actionRestart()
    {
        $this->sendSignal(SIGTERM);
        $time = 0;
        while (posix_getpgid($this->getPid()) && $time <= 10) {
            usleep(100000);
            $time++;
        }
        if ($time > 100) {
            $this->stderr("服务停止超时..." . PHP_EOL);
            exit(1);
        }
        if ($this->getPid() === false) {
            $this->stdout("服务重启成功..." . PHP_EOL);
        } else {
            $this->stderr("服务停止错误, 请手动处理杀死进程..." . PHP_EOL);
        }

        $this->actionStart();
    }

    /**
     * 发送信号
     *
     * @param $sig
     */
    private function sendSignal($sig)
    {
        if ($pid = $this->getPid()) {
            posix_kill($pid, $sig);
        } else {
            $this->stdout("服务未运行..." . PHP_EOL);
            exit(1);
        }
    }

    /**
     * 获取pid进程
     *
     * @return bool|string
     */
    private function getPid()
    {
        $pid_file = $this->config['pid_file'];
        if (file_exists($pid_file)) {
            $pid = file_get_contents($pid_file);
            if (posix_getpgid($pid)) {
                return $pid;
            } else {
                unlink($pid_file);
            }
        }

        return false;
    }

    /**
     * 写入pid进程
     *
     * @throws \yii\base\Exception
     */
    private function setPid()
    {
        $parentPid = getmypid();
        $pidDir = dirname($this->config['pid_file']);
        if (!file_exists($pidDir)) {
            FileHelper::createDirectory($pidDir);
        }

        file_put_contents($this->config['pid_file'], $parentPid + 1);
    }
}