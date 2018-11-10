<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\member\MemberInfo;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => Yii::$app->debris->config('smtp_host'),
                'username' => Yii::$app->debris->config('smtp_username'),
                'password' => Yii::$app->debris->config('smtp_password'),
                'port' => Yii::$app->debris->config('smtp_port'),
                'encryption' => empty(Yii::$app->debris->config('smtp_encryption')) ? 'tls' : 'ssl',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\member\MemberInfo',
                'filter' => ['status' => MemberInfo::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function sendEmail()
    {
        /* @var $user MemberInfo */
        $user = MemberInfo::findOne([
            'status' => MemberInfo::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!MemberInfo::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
