<?php

namespace addons\RfExample\common\models;

use Yii;

/**
 * This is the model class for table "{{%addon_example_cate}}".
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
class Cate extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_example_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
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
}
