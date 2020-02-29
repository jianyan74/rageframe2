<?php

namespace common\models\member;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%member_level}}".
 *
 * @property int $id 主键
 * @property string $merchant_id 商户id
 * @property int $level 等级（数字越大等级越高）
 * @property string $name 等级名称
 * @property string $money 消费额度满足则升级
 * @property int $check_money 选中消费额度
 * @property int $integral 消费积分满足则升级
 * @property int $check_integral 选中消费积分
 * @property int $middle 条件（0或 1且）
 * @property string $discount 折扣
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $detail 会员介绍
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Level extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_level}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level'], 'unique'],
            [
                [
                    'merchant_id', 'level', 'check_money', 'integral', 'check_integral',
                    'middle', 'status', 'created_at', 'updated_at'
                ], 'integer'
            ],
            [['level', 'discount', 'name'], 'required'],
            [['money'], 'number'],
            [['discount'], 'number', 'min' => 1, 'max' => 100],
            [['name', 'detail'], 'string', 'max' => 255],
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
            'level' => '等级', // （数字越大等级越高）
            'name' => '等级名称',
            'money' => '消费额度满足则升级',
            'check_money' => '选中消费额度',
            'integral' => '消费积分满足则升级',
            'check_integral' => '选中消费积分',
            'middle' => '条件（0或 1且）',
            'discount' => '折扣',
            'status' => '状态',
            'detail' => '会员介绍',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}