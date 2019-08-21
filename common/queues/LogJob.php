<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;

/**
 * Class LogJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class LogJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 日志记录数据
     *
     * @var
     */
    public $data;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->services->log->realCreate($this->data);
    }
}