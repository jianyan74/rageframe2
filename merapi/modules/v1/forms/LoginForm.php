<?php

namespace merapi\modules\v1\forms;

use common\enums\StatusEnum;
use common\models\merchant\Member;
use common\enums\AccessTokenGroupEnum;

/**
 * Class LoginForm
 * @package merapi\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LoginForm extends \common\models\forms\LoginForm
{
    public $group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'group'], 'required'],
            ['password', 'validatePassword'],
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录帐号',
            'password' => '登录密码',
            'group' => '组别',
        ];
    }

    /**
     * 用户登录
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false) {
            // email 登录
            if (strpos($this->username, "@")) {
                $this->_user = Member::findOne(['email' => $this->username, 'status' => StatusEnum::ENABLED]);
            } else {
                $this->_user = Member::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}
