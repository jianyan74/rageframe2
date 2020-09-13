<?php

namespace common\models\common;

use common\behaviors\MerchantBehavior;
use Yii;

/**
 * This is the model class for table "{{%common_commission_withdraw_log}}".
 *
 * @property int $id
 * @property int $merchant_id 商户id
 * @property int $member_id 用户id
 * @property string $app_id 应用id
 * @property string $addons_name 插件名称
 * @property string $withdraw_no 关联订单号
 * @property string $withdraw_group 组别[默认统一支付类型]
 * @property string $enc_bank_name 银行
 * @property string $enc_bank_no 卡号
 * @property string $enc_true_name 真实姓名
 * @property string $openid openid
 * @property string $mch_id 商户支付账户
 * @property string $body 支付内容
 * @property string $detail 支付详情
 * @property string $out_trade_no 商户订单号
 * @property string $transaction_id 微信订单号
 * @property string $total_fee 微信充值金额
 * @property int $pay_type 支付类型[1:微信;2:支付宝;3:银联]
 * @property string $pay_fee 支付金额
 * @property int $pay_status 支付状态
 * @property int $pay_time 支付时间
 * @property string $trade_type 交易类型
 * @property string $create_ip 创建者ip
 * @property string $pay_ip 支付者ip
 * @property string $notify_url 支付通知回调地址
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class CommissionWithdrawLog extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_commission_withdraw_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'pay_type', 'pay_status', 'pay_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_fee', 'pay_fee'], 'number'],
            [['app_id', 'enc_bank_no', 'enc_true_name', 'openid', 'transaction_id'], 'string', 'max' => 50],
            [['addons_name', 'body', 'detail', 'notify_url'], 'string', 'max' => 100],
            [['withdraw_no', 'create_ip', 'pay_ip'], 'string', 'max' => 30],
            [['withdraw_group', 'mch_id'], 'string', 'max' => 20],
            [['enc_bank_name'], 'string', 'max' => 255],
            [['out_trade_no'], 'string', 'max' => 32],
            [['trade_type'], 'string', 'max' => 16],
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
            'app_id' => '应用id',
            'addons_name' => '插件名称',
            'withdraw_no' => '关联订单号',
            'withdraw_group' => '组别[默认统一支付类型]',
            'enc_bank_name' => '银行',
            'enc_bank_no' => '卡号',
            'enc_true_name' => '真实姓名',
            'openid' => 'openid',
            'mch_id' => '商户支付账户',
            'body' => '支付内容',
            'detail' => '支付详情',
            'out_trade_no' => '商户订单号',
            'transaction_id' => '微信订单号',
            'total_fee' => '微信充值金额',
            'pay_type' => '支付类型[1:微信;2:支付宝;3:银联]',
            'pay_fee' => '支付金额',
            'pay_status' => '支付状态',
            'pay_time' => '支付时间',
            'trade_type' => '交易类型',
            'create_ip' => '创建者ip',
            'pay_ip' => '支付者ip',
            'notify_url' => '支付通知回调地址',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
