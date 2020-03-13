<?php

namespace common\models\common;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_pay_log}}".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户id
 * @property int $member_id 用户id
 * @property string $addons_name 插件名称
 * @property string $order_sn 关联订单号
 * @property string $order_group 组别[默认统一支付类型]
 * @property string $openid openid
 * @property string $mch_id 商户支付账户
 * @property string $body 支付内容
 * @property string $detail 支付详情
 * @property string $auth_code 刷卡码
 * @property string $out_trade_no 商户订单号
 * @property string $transaction_id 微信订单号
 * @property string $total_fee 微信充值金额
 * @property string $fee_type 标价币种
 * @property int $pay_type 支付类型[1:微信;2:支付宝;3:银联]
 * @property string $pay_fee 支付金额
 * @property int $pay_status 支付状态
 * @property int $pay_time 创建时间
 * @property string $trade_type 交易类型
 * @property string $refund_sn 退款编号
 * @property string $refund_fee 退款金额
 * @property int $is_refund 退款情况[0:未退款;1已退款]
 * @property string $create_ip 创建者ip
 * @property string $pay_ip 支付者ip
 * @property string $notify_url 支付通知回调地址
 * @property string $return_url 买家付款成功跳转地址
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class PayLog extends \common\models\base\BaseModel
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
            [['addons_name', 'body', 'detail', 'refund_sn', 'notify_url', 'return_url'], 'string', 'max' => 100],
            [['order_group', 'mch_id'], 'string', 'max' => 20],
            [['openid', 'auth_code', 'transaction_id'], 'string', 'max' => 50],
            [['out_trade_no'], 'string', 'max' => 32],
            [['fee_type'], 'string', 'max' => 10],
            [['trade_type'], 'string', 'max' => 16],
            [['order_sn', 'create_ip', 'pay_ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'merchant_id' => '商户id',
            'member_id' => '用户id',
            'addons_name' => '插件名称',
            'order_sn' => '关联订单号',
            'order_group' => '组别[默认统一支付类型]',
            'openid' => 'openid',
            'mch_id' => '商户支付账户',
            'body' => '支付内容',
            'detail' => '支付详情',
            'auth_code' => '刷卡码',
            'out_trade_no' => '商户订单号',
            'transaction_id' => '微信订单号',
            'total_fee' => '微信充值金额',
            'fee_type' => '标价币种',
            'pay_type' => '支付类型[1:微信;2:支付宝;3:银联]',
            'pay_fee' => '支付金额',
            'pay_status' => '支付状态',
            'pay_time' => '创建时间',
            'trade_type' => '交易类型',
            'refund_sn' => '退款编号',
            'refund_fee' => '退款金额',
            'is_refund' => '退款情况[0:未退款;1已退款]',
            'create_ip' => '创建者ip',
            'pay_ip' => '支付者ip',
            'notify_url' => '支付通知回调地址',
            'return_url' => '买家付款成功跳转地址',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->create_ip = Yii::$app->request->userIP;
            $this->app_id = Yii::$app->id;
            $this->addons_name = Yii::$app->params['addon']['name'] ?? '';
        }

        return parent::beforeSave($insert);
    }
}