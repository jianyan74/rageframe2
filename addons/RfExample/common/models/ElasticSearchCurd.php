<?php
namespace addons\RfExample\common\models;

use Yii;
use yii\elasticsearch\ActiveRecord;

/**
 * Class ElasticSearchCurd
 * @package addons\RfExample\common\models
 */
class ElasticSearchCurd extends ActiveRecord
{
    public static $currentIndex;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        // 配置了es的集群，那么需要在http_address中把每一个节点的ip都要配置上
        Yii::$app->set('elasticsearch', [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '192.168.0.199:9200'],
                ['http_address' => '192.168.0.210:9200'],
            ],
        ]);
    }

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
                'customer_id' => ['type' => 'long',  "index" => "not_analyzed"],
                'uuids' => ['type' => 'string',  "index" => "not_analyzed"],
                'updated_at' => ['type' => 'long',  "index" => "not_analyzed"],
                'emails' => ['type' => 'string',"index" => "not_analyzed"],
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
        if (!$command->indexExists(self::index()))
        {
            $command->createIndex(self::index());
        }

        $command->setMapping(self::index(), self::type(), self::mapping());
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
}