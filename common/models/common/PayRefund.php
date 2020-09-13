<?php

namespace common\models\common;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%common_pay_refund}}".
 *
 * @property int $id 主键id
 * @property int $merchant_id 商户id
 * @property int $member_id 买家id
 * @property string $app_id 应用id
 * @property string $order_sn 关联订单号
 * @property int $pay_id 支付ID
 * @property string $refund_trade_no 退款交易号
 * @property string $refund_money 退款金额
 * @property int $refund_way 退款方式
 * @property string $ip 申请者ip
 * @property string $remark 备注
 * @property int $created_at
 * @property int $updated_at
 */
class PayRefund extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_pay_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'pay_id', 'refund_way', 'created_at', 'updated_at'], 'integer'],
            [['refund_money'], 'number'],
            [['app_id'], 'string', 'max' => 50],
            [['order_sn', 'ip'], 'string', 'max' => 30],
            [['refund_trade_no'], 'string', 'max' => 55],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'merchant_id' => '商户id',
            'member_id' => '买家id',
            'app_id' => '应用id',
            'order_sn' => '关联订单号',
            'pay_id' => '支付ID',
            'refund_trade_no' => '退款交易号',
            'refund_money' => '退款金额',
            'refund_way' => '退款方式',
            'ip' => '申请者ip',
            'remark' => '备注',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
