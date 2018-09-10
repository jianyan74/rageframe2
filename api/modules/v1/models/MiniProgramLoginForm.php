<?php
namespace api\modules\v1\models;

use yii\base\Model;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iv', 'rawData', 'encryptedData', 'signature', 'auth_key'], 'required'],
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
        ];
    }
}