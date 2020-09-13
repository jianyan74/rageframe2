<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_bank_number}}".
 *
 * @property int $id
 * @property string $bank_name 银行名称
 * @property string $bank_number 银行编号
 * @property int $type 银行卡类型：1:微信；2:支付宝
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class BankNumber extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_bank_number}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['bank_name', 'bank_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank_name' => '银行名称',
            'bank_number' => '银行编号',
            'type' => '银行卡类型：1:微信；2:支付宝',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
