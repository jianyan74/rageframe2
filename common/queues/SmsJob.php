<?php
namespace common\queues;

use Yii;
use yii\base\BaseObject;

/**
 * Class SmsJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class SmsJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    public $usage;

    /**
     * @var
     */
    public $member_id;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function execute($queue)
    {
       Yii::$app->services->sms->realSend($this->mobile, $this->code, $this->usage, $this->member_id);
    }
}