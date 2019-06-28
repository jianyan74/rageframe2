<?php
namespace addons\RfArticle\common\models;

use Yii;
use common\behaviors\MerchantBehavior;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%addon_article_tag}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ArticleTag extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_article_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 20],
            [['title'], 'required'],
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
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关联中间表
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagMap()
    {
        return $this->hasOne(ArticleTagMap::class, ['tag_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getCheckTags()
    {
        // 文章标签
        $articleTags = ArticleTag::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->select(['id', 'title'])
            ->asArray()
            ->all();

        $tags = [];
        foreach ($articleTags as $tag) {
            $tags[$tag['id']] = $tag['title'];
        }

        return $tags;
    }
}
