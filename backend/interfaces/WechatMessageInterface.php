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
     * @param $message
     * @return mixed
     */
    public function run($message);
}