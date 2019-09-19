<?php

namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%sys_notify_pull_time}}".
 *
 * @property int $manager_id
 * @property int $type 消息类型[1:公告;2:提醒;3:信息(私信)
 * @property string $alert_type 提醒消息类型[sys:系统;wechat:微信]
 * @property int $last_time 最后拉取时间
 */
class NotifyPullTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_notify_pull_time}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manager_id'], 'required'],
            [['manager_id', 'type', 'last_time'], 'integer'],
            [['alert_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'manager_id' => 'Manager ID',
            'type' => '消息类型',
            'alert_type' => '提醒消息类型',
            'last_time' => '最后拉取时间',
        ];
    }
}
