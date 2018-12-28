<?php
namespace frontend\models;

use common\enums\StatusEnum;
use common\models\member\MemberInfo;

/**
 * Class LoginForm
 * @package frontend\models
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
            'rememberMe' => '记住我',
        ];
    }

    /**
     * 邮箱或账号登录
     *
     * @return MemberInfo|mixed|null
     */
    public function getUser()
    {
        if ($this->_user == false)
        {
            if (strpos($this->username, "@"))
            {
                $this->_user = MemberInfo::findOne(['email'=>$this->username, 'status' => StatusEnum::ENABLED]); // email 登录
            }
            else
            {
                $this->_user = MemberInfo::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}
