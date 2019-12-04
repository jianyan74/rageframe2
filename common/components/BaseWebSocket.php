<?php

namespace common\components;

use Swoole\WebSocket\Server;

/**
 * Class Test
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class BaseWebSocket
{
    /**
     * @var Server
     */
    public $server;
    public $frame;
    public $content;

    /**
     * 测试
     */
    public function actionIndex()
    {
        $this->server->push($this->frame->fd, '测试');
    }
}