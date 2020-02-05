<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;
use common\models\common\ActionLog;

/**
 * Class ActionLogJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class ActionLogJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 行为日志级别
     *
     * @var
     */
    public $level;

    /**
     * 行为日志
     *
     * @var ActionLog
     */
    public $actionLog;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->services->actionLog->realCreate($this->actionLog, $this->level);
    }
}