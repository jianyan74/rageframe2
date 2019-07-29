<?php
namespace addons\RfArticle\common\models;

use Yii;
use common\behaviors\MerchantBehavior;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%addon_article}}".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $cover 封面
 * @property string $seo_key seo关键字
 * @property string $seo_content seo内容
 * @property int $cate_id 分类id
 * @property string $description 描述
 * @property int $position 推荐位
 * @property string $content 文章内容
 * @property string $link 外链
 * @property string $author 作者
 * @property int $view 浏览量
 * @property int $sort 优先级
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Article extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    public $tags = [];

    /**
     * 推荐位(位运算)
     *
     * @var array
     */
    public static $positionExplain = [
        '1' => "首页",
        '2' => "列表",
        '4' => "内页",
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cover', 'sort'], 'required'],
            [['merchant_id', 'cate_id', 'view', 'sort', 'status', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['position', 'created_at', 'tags'], 'safe'],
            [['title', 'seo_key'], 'string', 'max' => 50],
            [['cover', 'link'], 'string', 'max' => 100],
            [['seo_content'], 'string', 'max' => 1000],
            [['description'], 'string', 'max' => 140],
            [['author'], 'string', 'max' => 40],
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
            'cover' => '封面',
            'seo_key' => 'Seo Key',
            'seo_content' => 'Seo Content',
            'tags' => '标签',
            'cate_id' => '分类',
            'description' => '简介',
            'position' => '推荐位',
            'content' => '内容',
            'link' => '外链',
            'author' => '作者',
            'view' => '浏览量',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 上一篇
     *
     * @param int $id 当前文章id
     * @return false|null|string
     */
    public static function getPrev($id)
    {
        return self::find()
            ->where(['<', 'id', $id])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->select(['id', 'title'])
            ->orderBy('id asc')
            ->one();
    }

    /**
     * 下一篇
     * scalar
     * @param int $id 当前文章id
     * @return false|null|string
     */
    public static function getNext($id)
    {
        return self::find()
            ->where(['>', 'id', $id])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->select(['id', 'title'])
            ->orderBy('id asc')
            ->one();
    }

    /**
     * 获取推荐位
     *
     * @param $position
     * @return string
     */
    public static function position($position)
    {
        return "position & {$position} = {$position}";
    }

    /**
     * 将两个参数进行按位与运算
     * 不为0则表示$contain属于$pos
     *
     * @param $pos
     * @param int $contain
     * @return bool
     */
    public static function checkPosition($pos, $contain = 0)
    {
        $res = $pos & $contain;
        return $res !== 0 ? true : false;
    }

    /**
     * 生成推荐位的值
     * @return int|mixed
     */
    protected function getPosition()
    {
        $position = $this->position;
        $pos = 0;
        if (!is_array($position)) {
            if ($position > 0) {
                return $position;
            }
        } else {
            foreach ($position as $key => $value) {
                // 将各个推荐位的值相加
                $pos += $value;
            }
        }

        return $pos;
    }

    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(ArticleCate::class,['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTags()
    {
        return $this->hasMany(ArticleTag::class, ['id' => 'tag_id'])
            ->viaTable(ArticleTagMap::tableName(), ['article_id' => 'id'])
            ->asArray();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // 推荐位
        $this->position = $this->getPosition();
        $this->created_at = StringHelper::dateToInt($this->created_at);

        return parent::beforeSave($insert);
    }
}
