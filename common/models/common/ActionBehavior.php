<?php

namespace common\models\common;

use common\enums\CacheEnum;
use Yii;

/**
 * This is the model class for table "{{%common_action_behavior}}".
 *
 * @property int $id 主键
 * @property string $app_id 应用id
 * @property string $url 提交url
 * @property string $remark
 * @property string $method 提交类型 *为不限
 * @property string $behavior 行为类别
 * @property int $action 前置/后置
 * @property int $is_record_post 是否记录post[0;否;1是]
 * @property int $is_ajax 是否ajax请求[0;否;1是;2不限]
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class ActionBehavior extends \common\models\base\BaseModel
{
    const ACTION_BEFORE = 1;
    const ACTION_AFTER = 2;

    public static $actionExplain = [
        self::ACTION_BEFORE => '前置方法',
        self::ACTION_AFTER => '后置方法',
    ];

    const AJAX_NO = 0;
    const AJAX_YES = 1;
    const AJAX_ALL = 2;

    /**
     * @var array
     */
    public static $ajaxExplain = [
        self::AJAX_NO => '否',
        self::AJAX_YES => '是',
        self::AJAX_ALL => '不限',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_action_behavior}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['behavior', 'url'], 'required'],
            [['action', 'is_record_post', 'is_ajax', 'status', 'created_at', 'updated_at'], 'integer'],
            [['app_id', 'behavior'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 100],
            [['method', 'level'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '应用',
            'url' => '请求Url',
            'method' => '请求方式',
            'behavior' => '标识',
            'remark' => '备注',
            'level' => '行为级别',
            'action' => '触发方式',
            'is_record_post' => 'Post数据记录',
            'is_ajax' => 'Ajax请求',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$this->app_id) {
            $this->app_id = Yii::$app->id;
        }

        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        Yii::$app->cache->set(
            CacheEnum::getPrefix('actionBehavior'),
            Yii::$app->services->actionBehavior->getAllData(),
            3600 * 2
        );

        parent::afterDelete();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->set(
            CacheEnum::getPrefix('actionBehavior'),
            Yii::$app->services->actionBehavior->getAllData(),
            3600 * 2
        );

        parent::afterSave($insert, $changedAttributes);
    }
}
