<?php
namespace addons\RfArticle\common\models;

use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_article_single}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $name 标识
 * @property string $seo_key seo关键字
 * @property string $seo_content seo内容
 * @property string $cover 封面
 * @property string $description 描述
 * @property string $content 文章内容
 * @property string $link 外链
 * @property int $display 可见性
 * @property string $author 作者
 * @property string $view 浏览量
 * @property int $sort 优先级
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ArticleSingle extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_article_single}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cover'], 'required'],
            [['content'], 'string'],
            [['merchant_id', 'display', 'view', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'seo_key'], 'string', 'max' => 50],
            [['name', 'author'], 'string', 'max' => 40],
            [['seo_content'], 'string', 'max' => 1000],
            [['cover', 'link'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'name' => '标识',
            'seo_key' => 'Seo Key',
            'seo_content' => 'Seo Content',
            'cover' => '封面',
            'description' => '简介',
            'content' => '内容',
            'link' => '外链',
            'display' => '可见',
            'author' => '作者',
            'view' => '浏览量',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
