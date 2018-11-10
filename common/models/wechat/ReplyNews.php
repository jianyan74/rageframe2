<?php

namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_reply_news}}".
 *
 * @property int $id
 * @property int $rule_id
 * @property int $attachment_id
 */
class ReplyNews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_reply_news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id', 'attachment_id'], 'integer'],
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
            'attachment_id' => '图文',
        ];
    }

    /**
     * 关联图文资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(AttachmentNews::className(),['attachment_id' => 'attachment_id'])->orderBy('id asc');
    }

    /**
     * 关联图文资源
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsTop()
    {
        return $this->hasOne(AttachmentNews::className(),['attachment_id' => 'attachment_id'])->where(['sort' => 0]);
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
