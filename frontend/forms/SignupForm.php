<?php
namespace frontend\forms;

use yii\base\Model;
use common\models\member\Member;

/**
 * Class SignupForm
 * @package frontend\models
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'trim'],
            [['email', 'username', 'password'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\member\Member', 'message' => '这个用户名已经被占用.'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\member\Member', 'message' => '这个邮箱地址已经被占用了.'],
            ['password', 'string', 'min' => 6, 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录帐号',
            'password' => '登录密码',
            'email' => '电子邮箱',
        ];
    }

    /**
     * 注册
     *
     * @return Member|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        $user = new Member();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
