<?php

namespace common\models\member;

use yii\db\ActiveRecord;
use common\behaviors\MerchantBehavior;


/**
 * This is the model class for table "{{%member_account}}".
 *
 * @property int $id
 * @property int $merchant_id 商户id
 * @property int $member_id 用户id
 * @property double $user_money 当前余额
 * @property double $accumulate_money 累计余额
 * @property double $give_money 累计赠送余额
 * @property double $consume_money 累计消费金额
 * @property double $frozen_money 冻结金额
 * @property int $user_integral 当前积分
 * @property int $accumulate_integral 累计积分
 * @property int $give_integral 累计赠送积分
 * @property string $consume_integral 累计消费积分
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
            [['merchant_id', 'member_id', 'user_integral', 'accumulate_integral', 'give_integral', 'frozen_integral', 'status'], 'integer'],
            [['user_money', 'accumulate_money', 'give_money', 'consume_money', 'frozen_money', 'consume_integral'], 'number'],
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
            'member_id' => '用户id',
            'user_money' => '当前余额',
            'accumulate_money' => '累计余额',
            'give_money' => '累计赠送余额',
            'consume_money' => '累计消费金额',
            'frozen_money' => '冻结金额',
            'user_integral' => '当前积分',
            'accumulate_integral' => '累计积分',
            'give_integral' => '累计赠送积分',
            'consume_integral' => '累计消费积分',
            'frozen_integral' => '冻结积分',
            'status' => '状态',
        ];
    }
}
