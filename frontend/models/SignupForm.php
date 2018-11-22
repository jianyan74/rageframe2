<?php
namespace frontend\models;

use yii\base\Model;
use common\models\member\MemberInfo;

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
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\member\MemberInfo', 'message' => '这个用户名已经被占用.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\member\MemberInfo', 'message' => '这个邮箱地址已经被占用了.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * 注册
     *
     * @return MemberInfo|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        $user = new MemberInfo();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
