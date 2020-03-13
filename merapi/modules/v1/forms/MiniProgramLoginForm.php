<?php

namespace merapi\modules\v1\forms;

use Yii;
use yii\base\Model;

/**
 * Class MiniProgramLoginForm
 * @package merapi\modules\v1\models
 */
class MiniProgramLoginForm extends Model
{
    public $iv;
    public $rawData;
    public $encryptedData;
    public $signature;
    public $code;

    public $auth;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iv', 'rawData', 'encryptedData', 'signature', 'code'], 'required'],
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
            'code' => 'code码',
            'auth' => '授权秘钥',
        ];
    }

    /**
     * @param $attribute
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function authVerify($attribute)
    {
        $auth = Yii::$app->wechat->miniProgram->auth->session($this->code);
        // 解析是否接口报错
        Yii::$app->debris->getWechatError($auth);

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