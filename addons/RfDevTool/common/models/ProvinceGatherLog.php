<?php

namespace addons\RfDevTool\common\models;

use Yii;

/**
 * This is the model class for table "{{%addon_dev_tool_provinces_gather_log}}".
 *
 * @property string $id
 * @property string $job_id 工作id
 * @property int $message_id 消息id
 * @property array $data 数据
 * @property string $url
 * @property int $max_level 最大级别
 * @property int $level 当前级别
 * @property int $reconnection 重连次数
 * @property string $remark 备注
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ProvinceGatherLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_dev_tool_province_gather_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'message_id', 'max_level', 'level', 'reconnection', 'status', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'safe'],
            [['url', 'remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => '数据',
            'remark' => '备注',
            'url' => 'Url',
            'max_level' => '最大级别',
            'level' => '当前级别',
            'reconnection' => '重连次数',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }
}
