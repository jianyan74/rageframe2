<?php
namespace addons\RfExample\common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\elasticsearch\ActiveRecord;

/**
 * Class ElasticSearchCurd
 * @package addons\RfExample\common\models
 */
class ElasticSearchCurd extends ActiveRecord
{
    public static $currentIndex;

    /**
     * @return null|object|\yii\elasticsearch\Connection
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('elasticsearch');
    }

    /**
     * 数据库名称
     * @return string
     */
    public static function index()
    {
        return 'rageframe';
    }

    /**
     * 表名
     *
     * @return string
     */
    public static function type()
    {
        return 'curd';
    }

    /**
     * 属性(表字段)
     *
     * @return array|string[]
     */
    public function attributes()
    {
        $mapConfig = self::mapConfig();
        return array_keys($mapConfig['properties']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cover'], 'required'],
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => '标题',
            'sort' => '排序',
            'content' => '内容',
            'status' => '状态',
            'cover' => '封面',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * mapping配置(表字段说明)
     *
     * 如果需要在mapping中添加其他的字段，那么添加后在运行一次updateMapping()
     * 另外需要注意的是：elasticSearch的mapping是不能删除的，建了就是建了，如果要删除，您只能删除index（相当于mysql的db）
     * 然后重建mapping，因此，您最好写一个脚本，执行es的所有model的mapping。
     *
     * @return array
     */
    public static function mapConfig()
    {
        return [
            'properties' => [
                // 不想进行分词等操作，想当成一个和数据库类似的搜索 设置为not_analyzed
                // index 默认可不设置
                'title' => ['type' => 'text'],
                'content' => ['type' => 'text'],
                'cover' => ['type' => 'keyword'],
                'sort' => ['type' => 'integer'],
                'status' => ['type' => 'integer'],
                'created_at' => ['type' => 'long'],
                'updated_at' => ['type' => 'long'],
            ]
        ];
    }

    /**
     * @return array
     */
    public static function mapping()
    {
        return [
            static::type() => self::mapConfig(),
        ];
    }

    /**
     * 更新字段
     *
     * @throws \yii\base\InvalidConfigException
     */
    public static function updateMapping()
    {
        $db = self::getDb();
        $command = $db->createCommand();
        if (!$command->indexExists(self::index())) {
            $command->createIndex(self::index());
        }

        $command->setMapping(self::index(), self::type(), self::mapping());
    }

    /**
     * 删除
     *
     * @throws \yii\base\InvalidConfigException
     */
    public static function deleteMapping()
    {
        $db = self::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index());
    }

    /**
     * 获取字段
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getMapping()
    {
        $db = self::getDb();
        $command = $db->createCommand();

        return $command->getMapping();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}