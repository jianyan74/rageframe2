<?php
namespace common\queues;

use yii\base\BaseObject;
use common\models\common\Log;

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
        $log = new Log();
        $log->attributes = $this->data;
        $log->save();
    }
}