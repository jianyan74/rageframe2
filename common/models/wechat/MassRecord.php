<?php

namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_mass_record}}".
 *
 * @property string $id
 * @property string $tag_name 标签名称
 * @property string $fans_num 粉丝数量
 * @property string $msg_id 微信消息id
 * @property string $msg_type 回复类别
 * @property string $content 内容
 * @property int $tag_id 标签id
 * @property string $attachment_id 资源id
 * @property string $media_id 媒体id
 * @property string $type 类别
 * @property string $send_time 发送时间
 * @property int $send_status 0未发送 1已发送
 * @property string $final_send_time 最终发送时间
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at
 * @property int $updated_at 修改时间
 */
class MassRecord extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_mass_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fans_num', 'msg_id', 'tag_id', 'attachment_id', 'send_time', 'send_status', 'final_send_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tag_name'], 'string', 'max' => 50],
            [['msg_type', 'media_type'], 'string', 'max' => 10],
            [['content'], 'string', 'max' => 10000],
            [['media_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_name' => 'Tag Name',
            'fans_num' => 'Fans Num',
            'msg_id' => 'Msg ID',
            'msg_type' => 'Msg Type',
            'content' => 'Content',
            'tag_id' => '粉丝标签',
            'attachment_id' => 'Attachment ID',
            'media_id' => 'Media ID',
            'media_type' => 'Type',
            'send_time' => 'Send Time',
            'send_status' => 'Send Status',
            'final_send_time' => 'Final Send Time',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
