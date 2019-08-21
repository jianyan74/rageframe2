<?php

namespace addons\RfDevTool\common\models;

use Yii;

/**
 * This is the model class for table "{{%addon_dev_tool_province_job}}".
 *
 * @property string $id
 * @property string $year 年份
 * @property int $max_level 数据级别
 * @property int $message_id 消息id
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ProvinceJob extends \common\models\base\BaseModel
{
    /**
     * @var array
     */
    public static $maxLevelExplain = [
        1 => '省',
        2 => '省·市',
        3 => '省·市·区',
        4 => '省·市·区·街道',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_dev_tool_province_job}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'max_level', 'message_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => '年份',
            'max_level' => '数据级别',
            'message_id' => '队列消息ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }
}
