<?php

namespace common\models\common;

use backend\components\Tree;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%common_config_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $pid 上级id
 * @property int $level 级别
 * @property int $sort 排序
 * @property string $tree 树
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class ConfigCate extends \common\models\base\BaseModel
{
    use Tree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_config_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'sort'], 'required'],
            [['pid', 'level', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['tree'], 'string', 'max' => 300],
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
            'pid' => '父级',
            'level' => '级别',
            'sort' => '排序',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasMany(Config::class, ['cate_id' => 'id'])->with('value')
            ->orderBy('sort asc')
            ->where(['status' => StatusEnum::ENABLED]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }
}
