<?php

namespace common\models\merchant;

use common\traits\Tree;

/**
 * This is the model class for table "{{%addon_shop_product_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $cover 封面图
 * @property int $sort 排序
 * @property int $level 级别
 * @property int $pid 上级id
 * @property int $index_block_status 首页块级状态 1=>显示
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Cate extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%merchant_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort', 'level', 'pid', 'index_block_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['cover'], 'string', 'max' => 255],
            [['tree'], 'string'],
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
            'sort' => '排序',
            'level' => '级别',
            'tree' => '树',
            'pid' => '父级',
            'index_block_status' => '首页显示',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
