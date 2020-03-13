<?php

namespace addons\Merchants\merapi\modules\v1\forms;

use common\enums\StatusEnum;
use common\models\merchant\Member;
use addons\TinyShop\common\enums\AccessTokenGroupEnum;

/**
 * Class LoginForm
 * @package merapi\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LoginForm extends \common\models\forms\LoginForm
{
    public $group;
    public $mobile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'password', 'group'], 'required'],
            ['password', 'validatePassword'],
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
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
            $this->_user = Member::find()->where([
                'mobile' => $this->mobile,
                'status' => StatusEnum::ENABLED,
            ])->one();
        }

        return $this->_user;
    }
}
