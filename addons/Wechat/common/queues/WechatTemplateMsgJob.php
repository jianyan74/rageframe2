<?php

namespace addons\Wechat\common\queues;

use Yii;
use yii\base\BaseObject;

/**
 * 发送微信模板消息
 *
 * Class WechatTemplateMsgJob
 * @package common\queues
 * @author kbdbxt
 */
class WechatTemplateMsgJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 数据
     *
     * @array
     */
    public $data;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->wechatService->wechatTemplateMsg->realSend($this->data);
    }
}