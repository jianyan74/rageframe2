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

        if (!$user)
        {
            return false;
        }
        
        if (!MemberInfo::isPasswordResetTokenValid($user->password_reset_token))
        {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $subject = 'Password reset for ' . Yii::$app->name;
        $template = 'passwordResetToken';
        // 发送邮件
        return Yii::$app->services
            ->mailer
            ->send($user, $this->email, $subject, $template);
    }
}
