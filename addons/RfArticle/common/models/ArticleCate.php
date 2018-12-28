<?php
namespace addons\RfArticle\common\models;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%addon_article_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property int $sort 排序
 * @property int $level 级别
 * @property int $pid 上级id
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ArticleCate extends \common\models\common\BaseModel
{
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
            [['sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
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
            'level' => '级别',
            'pid' => 'Pid',
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
            ->asArray()
            ->all();

        return ArrayHelper::itemsMerge($cates);
    }

    /**
     * 获取下拉列表
     *
     * @return array
     */
    public static function getDropDown()
    {
        $cates = self::getTree();

        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($cates), 'id', 'title');
    }

    /**
     * 删除全部子类
     *
     * @return bool
     */
    public function beforeDelete()
    {
        $ids = ArrayHelper::getChildIds(self::find()->all(), $this->id);
        self::deleteAll(['in', 'id', $ids]);

        return parent::beforeDelete();
    }
}
