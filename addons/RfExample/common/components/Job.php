<?php
namespace addons\RfExample\common\components;

use yii\base\BaseObject;

/**
 * Class Job
 * @package addons\RfExample\components
 */
class Job extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 内容
     *
     * @var
     */
    public $content;

    /**
     * 文件路径
     *
     * @var
     */
    public $file;

    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {
        file_put_contents($this->file, $this->content);
    }
}