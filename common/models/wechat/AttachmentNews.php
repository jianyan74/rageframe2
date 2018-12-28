<?php
namespace common\models\wechat;

use yii\helpers\Url;

/**
 * This is the model class for table "{{%wechat_attachment_news}}".
 *
 * @property int $id
 * @property string $attachment_id 关联的资源id
 * @property string $title 标题
 * @property string $thumb_media_id 图文消息的封面图片素材id（必须是永久mediaID）
 * @property string $thumb_url 缩略图Url
 * @property string $author 作者
 * @property string $digest 简介
 * @property int $show_cover_pic 0为false，即不显示，1为true，即显示
 * @property string $content 图文消息的具体内容，支持HTML标签，必须少于2万字符
 * @property string $content_source_url 图文消息的原文地址，即点击“阅读原文”后的URL
 * @property string $media_url 资源Url
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class AttachmentNews extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_attachment_news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attachment_id', 'show_cover_pic', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['thumb_media_id', 'thumb_url', 'digest', 'content_source_url', 'media_url'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attachment_id' => '关联id',
            'title' => '标题',
            'thumb_media_id' => '缩略图资源id',
            'thumb_url' => '缩略图 Url',
            'author' => '作者',
            'digest' => 'Digest',
            'show_cover_pic' => '封面',
            'content' => '内容',
            'content_source_url' => '外链',
            'media_url' => '资源 Url',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 返回素材列表
     *
     * @param int $sort
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($sort = 0)
    {
       return self::find()->where(['sort' => $sort])
            ->orderBy('id asc')
            ->asArray()
            ->all();
    }

    /**
     * 返回素材列表
     *
     * @param $attachment_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getEditList($attachment_id)
    {
        $list = self::find()->where(['attachment_id' => $attachment_id])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        foreach ($list as &$item)
        {
            $item['thumb_url'] = urldecode(Url::to(['analysis/image', 'attach' => $item['thumb_url']]));
            preg_match_all('/<img[^>]*src\s*=\s*([\'"]?)([^\'" >]*)\1/isu', $item['content'], $match);

            $match_arr = [];
            foreach ($match[2] as $vo)
            {
                $match_arr[$vo] = $vo;
            }

            foreach ($match_arr as $src)
            {
                $url = Url::to(['analysis/image', 'attach' => $src]);
                $url = urldecode($url);
                $item['content'] =  str_replace($src,$url,$item['content']);
            }
        }

        return $list;
    }

    /**
     * @param $attachment_id
     * @return AttachmentNews|null
     */
    public static function findModel($attachment_id)
    {
        if (empty($attachment_id) || empty(($model = self::findOne($attachment_id))))
        {
            $model = new self();
            return $model->loadDefaultValues();
        }

        return $model;
    }

    /**
     * 关联素材
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachment::className(), ['id' => 'attachment_id']);
    }
}
