<?php

namespace backend\modules\base\forms;

use Yii;
use yii\base\Model;
use common\models\backend\Member;

/**
 * 修改密码表单
 *
 * Class PasswdForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class PasswdForm extends Model
{
    public $passwd;

    public $passwd_new;

    public $passwd_repetition;

    /**
     * @var Member
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['passwd', 'passwd_new', 'passwd_repetition'], 'filter', 'filter' => 'trim'],
            [['passwd', 'passwd_new', 'passwd_repetition'], 'required'],
            [['passwd', 'passwd_new', 'passwd_repetition'], 'string', 'min' => 6, 'max' => 15],
            [['passwd_repetition'], 'compare', 'compareAttribute' => 'passwd_new'],// 验证新密码和重复密码是否相等
            ['passwd', 'validatePassword'],
            ['passwd_new', 'notCompare'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'passwd' => '原密码',
            'passwd_new' => '新密码',
            'passwd_repetition' => '重复密码',
        ];
    }

    /**
     * @param $attribute
     */
    public function notCompare($attribute)
    {
        if ($this->passwd == $this->passwd_new) {
            $this->addError($attribute, '新密码不能和原密码相同');
        }
    }

    /**
     * 验证原密码是否正确
     *
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->passwd)) {
                $this->addError($attribute, '原密码不正确');
            }
        }
    }

    /**
     * 获取用户信息
     *
     * @return Member|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            /** @var Member $identity */
            $identity = Yii::$app->user->identity;
            $this->_user = Member::findByUsername($identity->username);
        }

        return $this->_user;
    }
}