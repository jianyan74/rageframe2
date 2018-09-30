<?php
namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_pay_log}}".
 *
 * @property int $id 主键
 * @property string $order_sn 关联订单号
 * @property int $order_group 组别[默认统一支付类型]
 * @property string $openid openid
 * @property string $mch_id 商户支付账户
 * @property string $out_trade_no 商户订单号
 * @property string $transaction_id 微信订单号
 * @property double $total_fee 微信充值金额
 * @property int $pay_type 支付类型[1:微信;2:支付宝;3:银联]
 * @property double $pay_fee 支付金额
 * @property string $fee_type 标价币种
 * @property int $pay_status 支付状态
 * @property string $trade_type 交易类型，取值为：JSAPI，NATIVE，APP等
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class PayLog extends \common\models\common\BaseModel
{
    const ORDER_GROUP = 1;
    const ORDER_GROUP_GOODS = 2;

    /**
     * 订单组别说明
     *
     * @var array
     */
    public static $orderGroupExplain = [
        self::ORDER_GROUP => '统一支付',
        self::ORDER_GROUP_GOODS => '商品',
    ];

    const PAY_TYPE = 0;
    const PAY_TYPE_WECHAT = 1;
    const PAY_TYPE_ALI = 2;
    const PAY_TYPE_UNION = 3;
    const PAY_TYPE_MINI_PROGRAM = 4;

    /**
     * 支付类型
     *
     * @var array
     */
    public static $payTypeExplain = [
        self::PAY_TYPE => '未支付',
        self::PAY_TYPE_WECHAT => '微信',
        self::PAY_TYPE_ALI => '支付宝',
        self::PAY_TYPE_UNION => '银联',
        self::PAY_TYPE_MINI_PROGRAM => '小程序',
    ];

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
            [['order_group', 'pay_type', 'pay_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_fee', 'pay_fee'], 'number'],
            [['order_sn', 'mch_id'], 'string', 'max' => 20],
            [['openid', 'transaction_id'], 'string', 'max' => 50],
            [['out_trade_no'], 'string', 'max' => 32],
            [['fee_type'], 'string', 'max' => 10],
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
            'order_sn' => '订单编号',
            'order_group' => '订单组别',
            'openid' => 'Openid',
            'mch_id' => '商户编号',
            'out_trade_no' => '支付订单号',
            'transaction_id' => 'Transaction ID',
            'total_fee' => '实际支付',
            'pay_type' => '支付类型',
            'pay_fee' => '支付金额',
            'fee_type' => '支付',
            'pay_status' => '支付状态',
            'trade_type' => '支付类别',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
