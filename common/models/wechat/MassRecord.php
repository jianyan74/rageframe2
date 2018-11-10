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
     * 消息类别
     */
    const MEDIA_TEXT = 'text';
    const MEDIA_NEWS = 'news';
    const MEDIA_IMAGES = 'image';
    const MEDIA_VOICE = 'voice';
    const MEDIA_VIDEO = 'video';

    /**
     * @var array
     * 说明
     */
    public static $mediaTypeExplain = [
        self::MEDIA_TEXT => '文字',
        self::MEDIA_IMAGES => '图片',
        self::MEDIA_NEWS => '图文',
        self::MEDIA_VOICE => '语音',
        self::MEDIA_VIDEO => '视频',
    ];
    
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
            [['fans_num', 'msg_id', 'tag_id', 'attachment_id', 'send_status', 'final_send_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tag_name'], 'string', 'max' => 50],
            [['msg_type', 'media_type'], 'string', 'max' => 10],
            [['content'], 'string', 'max' => 10000],
            [['media_id'], 'string', 'max' => 100],
            [['error_content'], 'string', 'max' => 255],
            [['send_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fans_num' => '粉丝数量',
            'msg_id' => '消息ID',
            'msg_type' => '消息类别',
            'content' => '内容',
            'tag_id' => '粉丝标签',
            'tag_name' => '标签名称',
            'attachment_id' => '资源id',
            'media_id' => '微信资源id',
            'media_type' => '资源类型',
            'send_time' => '发送时间',
            'send_status' => '发送状态',
            'final_send_time' => '实际发送时间',
            'error_content' => '报错原因',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
