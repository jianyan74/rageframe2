<?php
namespace api\modules\v1\models;

use common\models\member\MemberInfo;

/**
 * Login form
 */
class LoginForm extends \common\models\common\LoginForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录帐号',
            'password' => '登录密码',
        ];
    }

    /**
     * 用户登陆
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false)
        {
            // email 登录
            if (strpos($this->username, "@"))
            {
                $this->_user = MemberInfo::findOne(['email' => $this->username]);
            }
            else
            {
                $this->_user = MemberInfo::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}
