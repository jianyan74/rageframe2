<?php
namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_reply_voice}}".
 *
 * @property int $id
 * @property int $rule_id 规则id
 * @property string $media_id
 */
class ReplyVoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_reply_voice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id'], 'integer'],
            [['media_id'], 'required'],
            [['media_id'], 'string', 'max' => 255],
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
            'media_id' => '语音',
        ];
    }

    /**
     * 关联素材
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachment::className(), ['media_id' => 'media_id']);
    }

    /**
     * 关联规则
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRule()
    {
        return $this->hasOne(Rule::className(), ['id' => 'rule_id']);
    }
}
