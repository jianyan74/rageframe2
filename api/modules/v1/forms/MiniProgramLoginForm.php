<?php

namespace api\modules\v1\forms;

use Yii;
use yii\base\Model;
use common\enums\CacheKeyEnum;

/**
 * Class MiniProgramLoginForm
 * @package api\modules\v1\models
 */
class MiniProgramLoginForm extends Model
{
    public $iv;
    public $rawData;
    public $encryptedData;
    public $signature;
    public $auth_key;

    public $auth;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iv', 'rawData', 'encryptedData', 'signature', 'auth_key'], 'required'],
            [['signature'], 'authVerify'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iv' => '加密算法的初始向量',
            'rawData' => '不包括敏感信息的原始数据字符串，用于计算签名',
            'encryptedData' => '包括敏感数据在内的完整用户信息的加密数据',
            'signature' => '签名',
            'auth_key' => '授权秘钥',
            'auth' => '授权秘钥',
        ];
    }

    /**
     * @param $attribute
     */
    public function authVerify($attribute)
    {
        if (!($auth = Yii::$app->cache->get(CacheKeyEnum::API_MINI_PROGRAM_LOGIN . $this->auth_key))) {
            $this->addError($attribute, 'auth_key已过期');
            return;
        }

        $sign = sha1(htmlspecialchars_decode($this->rawData . $auth['session_key']));
        if ($sign !== $this->signature) {
            $this->addError($attribute, '签名错误');
            return;
        }

        $this->auth = $auth;
    }

    /**
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     */
    public function getUser()
    {
        return Yii::$app->wechat->miniProgram->encryptor->decryptData($this->auth['session_key'], $this->iv, $this->encryptedData);
    }
}