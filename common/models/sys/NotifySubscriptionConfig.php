<?php

namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%member_notify_subscription_config}}".
 *
 * @property string $manager_id 用户id
 * @property string $action 订阅事件
 */
class NotifySubscriptionConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_notify_subscription_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manager_id'], 'integer'],
            [['action'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'manager_id' => '用户id',
            'action' => 'Action',
        ];
    }
}
