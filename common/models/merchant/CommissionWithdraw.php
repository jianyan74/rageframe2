<?php

namespace common\models\merchant;

/**
 * This is the model class for table "{{%addon_tiny_distribution_balance_withdraw}}".
 *
 * @property int $id
 * @property int $merchant_id 商户id
 * @property string $withdraw_no 提现流水号
 * @property int $member_id 会员id
 * @property string $bank_name 提现银行名称
 * @property string $account_number 提现银行账号
 * @property string $realname 提现账户姓名
 * @property string $mobile 手机
 * @property string $cash 提现金额
 * @property string $memo 备注
 * @property int $state 当前状态 0已申请(等待处理) 1已同意 -1 已拒绝
 * @property int $payment_date 到账日期
 * @property int $transfer_type 转账方式   1 线下转账  2线上转账
 * @property string $transfer_name 转账银行名称
 * @property string $transfer_money 转账金额
 * @property int $transfer_status 转账状态 0未转账 1已转账 -1转账失败
 * @property string $transfer_remark 转账备注
 * @property string $transfer_result 转账结果
 * @property string $transfer_no 转账流水号
 * @property string $transfer_account_no 转账银行账号
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class CommissionWithdraw extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%merchant_commission_withdraw}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'state', 'payment_date', 'transfer_type', 'transfer_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['cash', 'transfer_money'], 'number'],
            [['withdraw_no', 'transfer_no', 'transfer_account_no'], 'string', 'max' => 100],
            [['bank_name', 'account_number', 'transfer_name'], 'string', 'max' => 50],
            [['realname'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 20],
            [['memo', 'transfer_remark', 'transfer_result'], 'string', 'max' => 200],
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
            'withdraw_no' => '提现流水号',
            'bank_name' => '提现银行',
            'account_number' => '提现账号',
            'realname' => '账户姓名',
            'mobile' => '手机',
            'cash' => '提现金额',
            'memo' => '备注',
            'state' => '当前状态',
            'payment_date' => '到账日期',
            'transfer_type' => '转账方式',
            'transfer_name' => '转账银行名称',
            'transfer_money' => '转账金额',
            'transfer_status' => '转账状态 0未转账 1已转账 -1转账失败',
            'transfer_remark' => '转账备注',
            'transfer_result' => '转账结果',
            'transfer_no' => '转账流水号',
            'transfer_account_no' => '转账银行账号',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }
}
