<?php

namespace common\models\backend;

use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%member_notify_subscription_config}}".
 *
 * @property string $member_id 用户id
 * @property string $action 订阅事件
 */
class NotifySubscriptionConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%backend_notify_subscription_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id'], 'integer'],
            [['action'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id' => '用户id',
            'action' => 'Action',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id'])->where(['status' => StatusEnum::ENABLED]);
    }
}
