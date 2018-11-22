<?php
namespace common\models\member;

/**
 * This is the model class for table "{{%member_credits_log}}".
 *
 * @property string $id
 * @property int $member_id 会员id
 * @property string $credit_type 变动类型[integral:积分;money:余额]
 * @property string $credit_group 变动的详细组别
 * @property string $remark 备注
 * @property double $old_num 之前的数据
 * @property double $new_num 变动后的数据
 * @property double $num 变动的数据
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class CreditsLog extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_credits_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['old_num', 'new_num', 'num'], 'number'],
            [['credit_type', 'credit_group'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 200],
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
            'credit_type' => '变动类型',
            'credit_group' => '变动类型分组',
            'remark' => '备注',
            'old_num' => '旧的数据',
            'new_num' => '新数据',
            'num' => '当前改动数据',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
