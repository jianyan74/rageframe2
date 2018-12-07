<?php

namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_reply_addon}}".
 *
 * @property int $id
 * @property int $rule_id 规则id
 * @property string $addon 模块名称
 */
class ReplyAddon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_reply_addon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id'], 'integer'],
            [['addon'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => '规则ID',
            'addon' => '模块标识',
        ];
    }
}
