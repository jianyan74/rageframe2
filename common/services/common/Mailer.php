<?php
namespace common\services\common;

use Yii;
use common\queues\MailerJob;
use common\services\Service;

/**
 * Class Mailer
 * @package common\services\common
 */
class Mailer extends Service
{
    /**
     * 消息队列
     *
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * 发送邮件
     *
     * ```php
     *       Yii::$app->services->mailer->send($user, $email, $subject, $template)
     * ```
     * @param object $user 用户信息
     * @param string $email 邮箱
     * @param string $subject 标题
     * @param string $template 对应邮件模板
     * @throws \yii\base\InvalidConfigException
     */
    public function send($user, $email, $subject, $template)
    {
        if ($this->queueSwitch == true)
        {
            $messageId = Yii::$app->queue->push(new MailerJob([
                'user' => $user,
                'email' => $email,
                'subject' => $subject,
                'template' => $template,
            ]));

            return $messageId;
        }
        else
        {
            $this->setConfig();
            $result = Yii::$app->mailer
                ->compose($template, ['user' => $user])
                ->setFrom([$this->config['smtp_username'] => $this->config['smtp_name']])
                ->setTo($email)
                ->setSubject($subject)
                ->send();

            return $result;
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function setConfig()
    {
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
}