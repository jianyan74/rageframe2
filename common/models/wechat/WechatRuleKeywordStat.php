<?php
namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_rule_keyword_stat}}".
 *
 * @property string $id
 * @property int $rule_id 规则id
 * @property string $keyword_id 关键字id
 * @property string $rule_name 规则名称
 * @property int $keyword_type 类别
 * @property string $keyword_content 触发的关键字内容
 * @property string $hit 关键字id
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class WechatRuleKeywordStat extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_rule_keyword_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id', 'keyword_id', 'keyword_type', 'hit', 'status', 'created_at', 'updated_at'], 'integer'],
            [['rule_name'], 'string', 'max' => 50],
            [['keyword_content'], 'string', 'max' => 255],
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
            'keyword_id' => '关键字id',
            'rule_name' => '规则名称',
            'keyword_type' => '关键字类型',
            'keyword_content' => '关键字内容',
            'hit' => '触发次数',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
