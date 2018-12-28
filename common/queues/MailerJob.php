<?php
namespace common\queues;

use Yii;
use yii\base\BaseObject;

/**
 * 发送邮件
 *
 * Class MailerJob
 * @package common\queues
 */
class MailerJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 当前用户信息
     *
     * @var
     */
    public $user;

    /**
     * 邮箱
     *
     * @var
     */
    public $email;

    /**
     * 主题(标题)
     *
     * @var
     */
    public $subject;

    /**
     * 邮件模板
     *
     * @var
     */
    public $template;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        $result = Yii::$app->services->mailer->realSend($this->user, $this->email, $this->subject, $this->template);
    }
}