<?php
namespace common\models\backend;

use Yii;

/**
 * This is the model class for table "{{%backend_notify_member}}".
 *
 * @property int $id
 * @property string $member_id 管理员id
 * @property int $notify_id 消息id
 * @property int $is_read 是否已读 1已读
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class NotifyMember extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%backend_notify_member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'notify_id', 'is_read', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '管理员',
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
    public function getNotifySenderForMember()
    {
        return $this->hasOne(Notify::class, ['id' => 'notify_id'])->with(['senderForMember']);
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
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

}
