<?php

namespace common\models\member;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%member_money_log}}".
 *
 * @property int $id
 * @property string $merchant_id
 * @property string $member_id 用户id
 * @property int $pay_type 支付类型
 * @property string $credit_group 变动的组别
 * @property string $credit_group_detail 变动的详细组别
 * @property double $num 变动的数据
 * @property string $remark 备注
 * @property string $ip ip地址
 * @property int $map_id 关联id
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class MoneyLog extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_money_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'member_id', 'pay_type', 'map_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['num'], 'number'],
            [['credit_group', 'credit_group_detail'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 20],
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
            'member_id' => 'Member ID',
            'pay_type' => '变动支付类型',
            'credit_group' => '操作类型',
            'credit_group_detail' => '操作详细类型',
            'ip' => 'Ip',
            'map_id' => '关联id',
            'num' => '变更数量',
            'remark' => '备注',
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
        if ($this->isNewRecord) {
            $this->ip = Yii::$app->request->userIP;
        }

        return parent::beforeSave($insert);
    }
}
