<?php

namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_reply_video}}".
 *
 * @property int $id
 * @property int $rule_id
 * @property string $title
 * @property string $description
 * @property string $media_id
 */
class ReplyVideo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_reply_video}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id'], 'integer'],
            [['title', 'description', 'media_id'], 'required'],
            [['title'], 'string', 'max' => 50],
            [['description', 'media_id'], 'string', 'max' => 255],
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
            'title' => '标题',
            'description' => '说明',
            'media_id' => '视频',
        ];
    }
}
