<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\UnprocessableEntityHttpException;
use common\models\member\MemberInfo;

/**
 * 密码重置表单
 *
 * Class ResetPasswordForm
 * @package frontend\models
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * ResetPasswordForm constructor.
     * @param $token
     * @param array $config
     * @throws UnprocessableEntityHttpException
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token))
        {
            throw new UnprocessableEntityHttpException('密码重置令牌不能为空.');
        }

        $this->_user = MemberInfo::findByPasswordResetToken($token);
        if (!$this->_user)
        {
            throw new UnprocessableEntityHttpException('密码重置令牌错误.');
        }

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
