<?php
namespace frontend\forms;

use Yii;
use common\enums\StatusEnum;
use common\models\member\Member;

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
     * @return Member|mixed|null
     */
    public function getUser()
    {
        if ($this->_user == false)
        {
            if (strpos($this->username, "@")) {
                $this->_user = Member::find()
                    ->where([
                        'email'=>$this->username,
                        'status' => StatusEnum::ENABLED,
                        'merchant_id' => Yii::$app->services->merchant->getId()
                    ])
                    ->one(); // email 登录
            } else {
                $this->_user = Member::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}
