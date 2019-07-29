<?php
namespace addons\RfArticle\common\models;

use Yii;
use backend\components\Tree;
use common\behaviors\MerchantBehavior;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%addon_article_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $tree 树
 * @property int $sort 排序
 * @property int $level 级别
 * @property int $pid 上级id
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ArticleCate extends \common\models\base\BaseModel
{
    use Tree, MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_article_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['title'], 'required'],
            [['tree'], 'string', 'max' => 500],
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
            'tree' => '树',
            'level' => '级别',
            'pid' => '父级',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 获取树状数据
     *
     * @return mixed
     */
    public static function getTree()
    {
        $cates = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->asArray()
            ->all();

        return ArrayHelper::itemsMerge($cates);
    }

    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public static function getEditDropDownList($id = '')
    {
        $list = self::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
        return ArrayHelper::merge([0 => '顶级分类'], $data);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDropDown()
    {
        $models = ArticleCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->orderBy('sort asc,created_at asc')
            ->asArray()
            ->all();

        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }
}
