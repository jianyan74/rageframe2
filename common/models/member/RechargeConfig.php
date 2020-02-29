<?php

namespace common\models\member;

use common\behaviors\MerchantBehavior;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%sys_recharge_config}}".
 *
 * @property string $id
 * @property string $price
 * @property string $give_price
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class RechargeConfig extends BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_recharge_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'required'],
            [['price', 'give_price'], 'number', 'min' => 0],
            [['sort', 'merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户',
            'price' => '充值金额',
            'give_price' => '赠送金额',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
