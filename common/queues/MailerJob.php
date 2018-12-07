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
     * 配置
     *
     * @var
     */
    protected $config = [];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->config = Yii::$app->debris->configAll();

        Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $this->config['smtp_host'],
                'username' => $this->config['smtp_username'],
                'password' => $this->config['smtp_password'],
                'port' => $this->config['smtp_port'],
                'encryption' => empty($this->config['smtp_encryption']) ? 'tls' : 'ssl',
            ],
        ]);
    }

    /**
     * @param \yii\queue\Queue $queue
     * @return bool|mixed|void
     */
    public function execute($queue)
    {
        $result = Yii::$app->mailer
            ->compose($this->template, ['user' => $this->user])
            ->setFrom([$this->config['smtp_username'] => $this->config['smtp_name']])
            ->setTo($this->email)
            ->setSubject($this->subject)
            ->send();
    }
}