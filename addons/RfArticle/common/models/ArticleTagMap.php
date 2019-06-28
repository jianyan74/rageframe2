<?php
namespace addons\RfArticle\common\models;

use Yii;

/**
 * This is the model class for table "{{%addon_article_tag_map}}".
 *
 * @property int $tag_id 标签id
 * @property int $article_id 文章id
 */
class ArticleTagMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_article_tag_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'article_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => '标签ID',
            'article_id' => '文章ID',
        ];
    }

    /**
     * @param $article_id
     * @return ArticleTagMap[]
     */
    public static function getTagsByActicleId($article_id)
    {
        return self::findAll(['article_id' => $article_id]);
    }

    /**
     * @param $article_id
     * @param $tags
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addTags($article_id, $tags)
    {
        // 删除原有标签关联
        self::deleteAll(['article_id' => $article_id]);
        if ($article_id && !empty($tags)) {
            $data = [];

            foreach ($tags as $v) {
                $data[] = [$v, $article_id];
            }

            $field = ['tag_id', 'article_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}
