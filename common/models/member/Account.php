<?php

namespace common\models\member;

use yii\db\ActiveRecord;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%member_account}}".
 *
 * @property string $id
 * @property string $merchant_id 商户id
 * @property string $member_id 用户id
 * @property string $user_money 余额
 * @property string $accumulate_money 累积余额
 * @property string $frozen_money 冻结金额
 * @property int $user_integral 当前积分
 * @property int $accumulate_integral 消费积分
 * @property int $frozen_integral 冻结积分
 */
class Account extends ActiveRecord
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'user_integral', 'accumulate_integral', 'frozen_integral'], 'integer'],
            [['user_money', 'accumulate_money', 'frozen_money'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'member_id' => '用户',
            'user_money' => '余额',
            'accumulate_money' => '累积余额',
            'frozen_money' => '冻结金额',
            'user_integral' => '当前积分',
            'accumulate_integral' => '消费积分',
            'frozen_integral' => '冻结积分',
        ];
    }
}
