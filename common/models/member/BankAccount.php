<?php

namespace common\models\member;

use common\enums\AccountTypeEnum;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%member_bank_account}}".
 *
 * @property int $id
 * @property int $member_id 会员id
 * @property string $branch_bank_name 支行信息
 * @property string $realname 真实姓名
 * @property string $account_number 银行账号
 * @property string $bank_code 银行编号
 * @property string $mobile 手机号
 * @property int $is_default 是否默认账号
 * @property int $account_type 账户类型，1：银行卡，2：微信，3：支付宝
 * @property string $account_type_name 账户类型名称：银行卡，微信，支付宝
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class BankAccount extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_bank_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'account_type', 'realname', 'mobile'], 'required'],
            [['member_id', 'merchant_id', 'is_default', 'account_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['branch_bank_name', 'ali_number', 'realname', 'account_number', 'bank_code'], 'string', 'max' => 50],
            [['mobile'], 'string', 'max' => 20],
            [['account_type_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员id',
            'merchant_id' => '商户id',
            'branch_bank_name' => '支行信息',
            'realname' => '真实姓名',
            'account_number' => '银行账号',
            'ali_number' => '支付宝账号',
            'bank_code' => '银行编号',
            'mobile' => '手机号',
            'is_default' => '是否默认账号',
            'account_type' => '账户类型',
            'account_type_name' => '账户类型名称',
            'status' => '状态',
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
        $this->account_type_name = AccountTypeEnum::getValue($this->account_type);

        if (($this->isNewRecord || $this->oldAttributes['is_default'] == StatusEnum::DISABLED) && $this->is_default == StatusEnum::ENABLED) {
            self::updateAll(['is_default' => StatusEnum::DISABLED], ['member_id' => $this->member_id, 'is_default' => StatusEnum::ENABLED]);
        }

        // 清空其他数据
        switch ($this->account_type) {
            case AccountTypeEnum::UNION :
                $this->ali_number = '';
                break;
            case AccountTypeEnum::ALI :
                $this->account_number = '';
                $this->branch_bank_name = '';
                break;
            default :
                $this->ali_number = '';
                $this->account_number = '';
                $this->branch_bank_name = '';
                break;
        }

        return parent::beforeSave($insert);
    }
}
