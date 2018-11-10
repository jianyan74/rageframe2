<?php
namespace common\models\wechat;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
class RuleKeywordStat extends \common\models\common\BaseModel
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
            'rule_id' => 'Rule ID',
            'keyword_id' => 'Keyword ID',
            'rule_name' => 'Rule Name',
            'keyword_type' => 'Keyword Type',
            'keyword_content' => 'Keyword Content',
            'hit' => 'Hit',
            'status' => 'Status',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 插入关键字统计
     *
     * @param $rule_id
     * @param $keyword_id
     */
    public static function setStat($rule_id, $keyword_id)
    {
        $ruleKeywordStat = RuleKeywordStat::find()
            ->where([
                'rule_id'=> $rule_id,
                'keyword_id' => $keyword_id,
                'created_at' => strtotime(date('Y-m-d'))
            ])
            ->one();

        if($ruleKeywordStat)
        {
            $ruleKeywordStat->hit += 1;
        }
        else
        {
            $ruleKeywordStat = new RuleKeywordStat();
            $ruleKeywordStat->rule_id = $rule_id;
            $ruleKeywordStat->keyword_id = $keyword_id;
        }

        $ruleKeywordStat->save();
    }

    /**
     * 关联规则
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRule()
    {
        return $this->hasOne(Rule::className(),['id' => 'rule_id']);
    }

    /**
     * 关联关键字
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuleKeyword()
    {
        return $this->hasOne(RuleKeyword::className(),['id' => 'keyword_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
            $this->created_at = strtotime(date('Y-m-d'));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
