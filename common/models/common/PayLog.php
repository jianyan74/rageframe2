<?php
namespace common\models\common;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_pay_log}}".
 *
 * @property int $id 主键
 * @property string $merchant_id 商户id
 * @property string $order_sn 关联订单号
 * @property int $order_group 组别[默认统一支付类型]
 * @property string $openid openid
 * @property string $mch_id 商户支付账户
 * @property string $out_trade_no 商户订单号
 * @property string $transaction_id 微信订单号
 * @property double $total_fee 微信充值金额
 * @property string $fee_type 标价币种
 * @property int $pay_type 支付类型[1:微信;2:支付宝;3:银联]
 * @property double $pay_fee 支付金额
 * @property int $pay_status 支付状态
 * @property int $pay_time 创建时间
 * @property string $trade_type 交易类型，取值为：JSAPI，NATIVE，APP等
 * @property string $refund_sn 退款编号
 * @property double $refund_fee 退款金额
 * @property int $is_refund 退款情况[0:未退款;1已退款]
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class PayLog extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_pay_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'pay_type', 'pay_status', 'pay_time', 'is_refund', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_fee', 'pay_fee', 'refund_fee'], 'number'],
            [['order_sn', 'mch_id', 'order_group'], 'string', 'max' => 20],
            [['openid', 'transaction_id'], 'string', 'max' => 50],
            [['out_trade_no'], 'string', 'max' => 32],
            [['fee_type'], 'string', 'max' => 10],
            [['trade_type'], 'string', 'max' => 16],
            [['refund_sn'], 'string', 'max' => 100],
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
            'member_id' => '用户id',
            'order_sn' => '订单编号',
            'order_group' => '订单组别',
            'openid' => 'Openid',
            'mch_id' => '商户编号',
            'out_trade_no' => '支付订单号',
            'transaction_id' => 'Transaction ID',
            'total_fee' => '实际支付',
            'fee_type' => 'Fee Type',
            'pay_type' => '支付类型',
            'pay_fee' => '支付金额',
            'pay_status' => '支付状态',
            'pay_time' => '支付时间',
            'trade_type' => 'Trade Type',
            'refund_sn' => 'Refund Sn',
            'refund_fee' => 'Refund Fee',
            'is_refund' => 'Is Refund',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
