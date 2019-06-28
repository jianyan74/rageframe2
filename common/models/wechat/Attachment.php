<?php
namespace common\models\wechat;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%wechat_attachment}}".
 *
 * @property string $id
 * @property string $file_name 文件原始名
 * @property string $local_url 本地地址
 * @property string $media_type 类别
 * @property string $media_id 微信资源ID
 * @property string $media_url 资源Url
 * @property string $width 宽度
 * @property string $height 高度
 * @property string $is_temporary 类型[临时:tmp永久:perm]
 * @property int $link_type 1微信2本地
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Attachment extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    const TYPE_NEWS = 'news';
    const TYPE_TEXT = 'text';
    const TYPE_VOICE = 'voice';
    const TYPE_IMAGE = 'image';
    const TYPE_CARD = 'card';
    const TYPE_VIDEO = 'video';

    /**
     * @var array
     */
    public static $typeExplain = [
        self::TYPE_NEWS => '图文素材',
        self::TYPE_IMAGE => '图片素材',
        // self::TYPE_TEXT => '文字素材',
        self::TYPE_VOICE => '音频素材',
        // self::TYPE_CARD => '卡卷素材',
         self::TYPE_VIDEO => '视频素材',
    ];

    const MODEL_PERM = 'perm';
    const MODEL_TMEP = 'tmep';

    /**
     * @var array
     */
    public static $modeExplain = [
        self::MODEL_PERM => '永久素材',
        self::MODEL_TMEP => '临时素材',
    ];

    /**
     * 微信图片前缀
     */
    const WECHAT_MEDIAT_URL = 'http://mmbiz.qpic.cn';

    const LINK_TYPE_WECHAT = 1;
    const LINK_TYPE_LOCAL = 2;

    public static $linkTypeExplain = [
        self::LINK_TYPE_WECHAT => '微信图文',
        self::LINK_TYPE_LOCAL => '本地图文',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'width', 'height', 'link_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['file_name', 'local_url', 'media_id'], 'string', 'max' => 150],
            [['media_id'], 'string', 'max' => 50],
            [['media_type'], 'string', 'max' => 15],
            [['media_url'], 'string', 'max' => 5000],
            [['description'], 'string', 'max' => 200],
            [['is_temporary'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => '文件名称',
            'local_url' => '本地素材',
            'media_type' => '资源类型',
            'media_id' => '资源id',
            'media_url' => '资源Url',
            'description' => '视频详情',
            'width' => '宽度',
            'height' => '高度',
            'is_temporary' => '是否临时',
            'link_type' => '是否微信图文',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关联图文
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(AttachmentNews::class, ['attachment_id' => 'id']);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterDelete()
    {
        AttachmentNews::deleteAll(['attachment_id' => $this->id]);

        if ($this->media_type == self::TYPE_NEWS) {
            Rule::deleteAll(['module' => $this->media_type, 'data' => $this->id]);
            MassRecord::deleteAll(['module' => $this->media_type, 'data' => $this->id]);
        } else {
            Rule::deleteAll(['module' => $this->media_type, 'data' => $this->media_type]);
            MassRecord::deleteAll(['module' => $this->media_type, 'data' => $this->media_type]);
        }

        parent::afterDelete();
    }
}
