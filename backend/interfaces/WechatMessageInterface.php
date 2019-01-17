<?php
namespace backend\interfaces;

/**
 * 微信消息接口
 *
 * Interface WechatMessageInterface
 * @package backend\interfaces
 */
interface WechatMessageInterface
{
    /**
     * 运行方法
     *
     * @param $message
     * @return mixed
     */
    public function run($message);
}