<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_auth_group}}".
 *
 * @property int $id 主键
 * @property string $title 分组名称
 * @property string $app_id 应用ID
 * @property int $is_free 是否免费，0：否，1：是
 * @property string $price 价格
 * @property int $count_unit 计算单位，0：天，1：周，2：月，3，季，4，年
 * @property int $status 状态，0：禁用，1：启用，-1：删除
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 最后更新时间
 */
class AuthGroup extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_auth_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_free', 'count_unit', 'status', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 100],
            [['app_id'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '分组标题',
            'app_id' => 'App ID',
            'is_free' => '免费',
            'price' => '价格',
            'count_unit' => '计算单位',
            'status' => '状态',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
