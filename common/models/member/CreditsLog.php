<?php

namespace common\models\member;

use Yii;

/**
 * This is the model class for table "{{%member_credits_log}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property string $member_id 用户id
 * @property string $app_id 应用
 * @property string $addons_name 插件
 * @property int $pay_type 支付类型
 * @property string $credit_type 变动类型[integral:积分;money:余额]
 * @property string $credit_group 变动的组别
 * @property double $old_num 之前的数据
 * @property double $new_num 变动后的数据
 * @property double $num 变动的数据
 * @property string $remark 备注
 * @property string $ip ip地址
 * @property string $map_id 关联id
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class CreditsLog extends \common\models\base\BaseModel
{
    // 金额类型
    const CREDIT_TYPE_USER_MONEY = 'user_money';
    const CREDIT_TYPE_GIVE_MONEY = 'give_money';
    const CREDIT_TYPE_CONSUME_MONEY = 'consume_money';

    // 积分类型
    const CREDIT_TYPE_USER_INTEGRAL = 'user_integral';
    const CREDIT_TYPE_GIVE_INTEGRAL = 'give_integral';

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
            [['pay_type', 'merchant_id', 'member_id', 'map_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['old_num', 'new_num', 'num'], 'number'],
            [['credit_type', 'credit_group', 'ip'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 200],
            [['app_id'], 'string', 'max' => 50],
            [['addons_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户',
            'member_id' => '用户',
            'pay_type' => '支付类型',
            'map_id' => '关联id',
            'ip' => 'ip地址',
            'credit_type' => '变动类型',
            'credit_group' => '操作类型',
            'old_num' => '变更之前',
            'new_num' => '变更后',
            'num' => '变更数量',
            'remark' => '备注',
            'status' => '状态',
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

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->app_id = Yii::$app->id;
            $this->addons_name = Yii::$app->params['addon']['name'] ?? '';
        }

        return parent::beforeSave($insert);
    }
}
