<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "{{%merchant}}".
 *
 * @property string $id
 * @property string $title 商户名称
 * @property string $user_money 当前余额
 * @property string $accumulate_money 累计余额
 * @property string $give_money 累计赠送余额
 * @property string $consume_money 累计消费金额
 * @property string $frozen_money 冻结金额
 * @property int $term_of_validity_type 有效期类型 0固定时间 1不限
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Merchant extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%merchant}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['user_money', 'accumulate_money', 'give_money', 'consume_money', 'frozen_money'], 'number'],
            [['term_of_validity_type', 'start_time', 'end_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '商户名称',
            'user_money' => '当前余额',
            'accumulate_money' => '累计余额',
            'give_money' => '累计赠送余额',
            'consume_money' => '累计消费金额',
            'frozen_money' => '冻结金额',
            'term_of_validity_type' => '有效期类型 0固定时间 1不限',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
