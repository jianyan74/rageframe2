<?php

namespace common\models\common;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_ip_blacklist}}".
 *
 * @property int $id
 * @property string $merchant_id
 * @property string $remark 备注
 * @property string $ip ip地址
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class IpBlacklist extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_ip_blacklist}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['ip'], 'trim'],
            [['ip'], 'verifyIp'],
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['remark'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'remark' => '备注',
            'ip' => 'IP',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param $attribute
     */
    public function verifyIp($attribute)
    {
        if ($this->ip == Yii::$app->request->userIP) {
            $this->addError($attribute, '请不要设置IP与你现在的IP一致');
        }
    }
}