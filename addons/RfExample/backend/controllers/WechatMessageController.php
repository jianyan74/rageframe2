<?php
namespace addons\RfExample\backend\controllers;

use backend\interfaces\WechatMessageInterface;

/**
 * Class WechatMessageController
 * @package addons\RfExample\backend\controllers
 */
class WechatMessageController implements WechatMessageInterface
{
    /**
     * @param string $message
     * @return int|mixed
     */
    public function run($message)
    {
        return 1;
    }
}