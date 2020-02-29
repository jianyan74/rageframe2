<?php

namespace common\websockets;

use common\components\BaseWebSocket;

/**
 * Class SiteWebsSocket
 * @package common\websockets
 * @author jianyan74 <751393839@qq.com>
 */
class SiteWebSocket extends BaseWebSocket
{
    /**
     * 用户登录
     *
     * {"route":"site.login",content:{'token':'',}}
     */
    public function actionLogin()
    {
        $content = $this->content;
        $token = $content['token'];

    }

    /**
     * 心跳包
     *
     * {"route":"site.ping"}
     */
    public function actionPing()
    {
        $this->server->push($this->frame->fd, $this->json(2002, 'ok'));
    }

    /**
     * 测试
     *
     * {"route":"site.test",content:{'token':'',}}
     */
    public function actionTest()
    {
        $this->server->push($this->frame->fd, $this->json(2000, 'test is success'));
    }
}