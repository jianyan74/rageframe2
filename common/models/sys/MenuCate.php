<?php
namespace common\models\sys;

use common\helpers\TreeHelper;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%sys_menu_cate}}".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property int $pid 上级id
 * @property int $level 级别
 * @property string $icon icon
 * @property string $tree 树
 * @property int $is_default_show 默认显示
 * @property int $is_addon 应用顶级分类
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class MenuCate extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_menu_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['level', 'pid', 'is_default_show', 'is_addon', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 300],
            [['level'], 'default', 'value' => 1],
            [['title', 'icon'], 'trim'],
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
            'icon' => '图标',
            'is_default_show' => '是否默认显示',
            'is_addon' => '应用中心',
            'sort' => '排序',
            'level' => '级别',
            'pid' => '上级id',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->is_default_show == StatusEnum::ENABLED) {
            self::updateAll(['is_default_show' => StatusEnum::DISABLED], ['is_default_show' => StatusEnum::ENABLED]);
        }

        if ($this->isNewRecord && $this->pid == 0) {
            $this->tree = TreeHelper::defaultTreeKey();
        }

        return parent::beforeSave($insert);
    }
}
