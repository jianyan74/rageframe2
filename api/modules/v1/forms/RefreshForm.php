<?php

namespace api\modules\v1\forms;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use common\models\api\AccessToken;
use common\models\member\Member;

/**
 * Class RefreshForm
 * @package api\modules\v1\models
 */
class RefreshForm extends Model
{
    public $group;
    public $refresh_token;

    protected $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['refresh_token', 'group'], 'required'],
            ['refresh_token', 'validateTime'],
            ['group', 'in', 'range' => AccessToken::$ruleGroupRnage]
        ];
    }

    public function attributeLabels()
    {
        return [
            'refresh_token' => '重置令牌',
            'group' => '组别',
        ];
    }

    /**
     * 验证过期时间
     *
     * @param $attribute
     * @throws BadRequestHttpException
     */
    public function validateTime($attribute)
    {
        if (!$this->hasErrors() && Yii::$app->params['user.refreshTokenValidity'] == true) {
            $token = $this->refresh_token;
            $timestamp = (int)substr($token, strrpos($token, '_') + 1);
            $expire = Yii::$app->params['user.refreshTokenExpire'];

            // 验证有效期
            if ($timestamp + $expire <= time()) {
                throw new BadRequestHttpException('您的重置令牌已经过期，请重新登陆');
            }
        }

        if (!$this->getUser()) {
            throw new BadRequestHttpException('找不到用户');
        }
    }

    /**
     * @return bool|Member|null|\yii\web\IdentityInterface
     */
    public function getUser()
    {
        if ($this->_user == false) {
            if (!($apiAccount = AccessToken::findIdentityByRefreshToken($this->refresh_token, $this->group))) {
                return false;
            }

            $this->_user = Member::findIdentity($apiAccount->member_id);
        }

        return $this->_user;
    }
}