<?php
namespace common\models\sys;

use Yii;

/**
 * This is the model class for table "{{%sys_notify_manager}}".
 *
 * @property int $id
 * @property string $manager_id 管理员id
 * @property int $notify_id 消息id
 * @property int $is_read 是否已读 1已读
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class NotifyManager extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_notify_manager}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manager_id', 'notify_id', 'is_read', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => '管理员',
            'notify_id' => 'Notify ID',
            'is_read' => '是否已读',
            'type' => '类别',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 关联消息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotify()
    {
        return $this->hasOne(Notify::class, ['id' => 'notify_id']);
    }

    /**
     * 关联消息和用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifySenderForManager()
    {
        return $this->hasOne(Notify::class, ['id' => 'notify_id'])->with(['senderForManager']);
    }

    /**
     * 关联消息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifySend()
    {
        return $this->hasOne(Notify::class, ['id' => 'notify_id']);
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::class, ['id' => 'manager_id']);
    }

}
