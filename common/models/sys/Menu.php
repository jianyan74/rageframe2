<?php
namespace common\models\sys;

use Yii;
use common\helpers\TreeHelper;

/**
 * This is the model class for table "{{%sys_menu}}".
 *
 * @property int $id
 * @property string $title 标题
 * @property int $cate_id 分类id
 * @property string $pid 上级id
 * @property string $url 路由
 * @property string $icon 样式
 * @property int $level 级别
 * @property int $dev 开发者[0:都可见;开发模式可见]
 * @property int $sort 排序
 * @property string $params 参数
 * @property string $tree 树
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class Menu extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'pid', 'cate_id'], 'required'],
            [['cate_id', 'pid', 'level', 'dev', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'safe'],
            [['title', 'url'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 20],
            [['tree'], 'string', 'max' => 300],
            [['level'], 'default', 'value' => 1],
            [['url', 'title', 'icon'], 'trim'],
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
            'cate_id' => '分类',
            'pid' => '父级',
            'tree' => '树',
            'url' => '路由',
            'icon' => '图标',
            'level' => '级别',
            'dev' => '开发可见',
            'sort' => '排序',
            'params' => '参数',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(MenuCate::class, ['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if ($this->pid == 0) {
                $this->tree = TreeHelper::defaultTreeKey();
            } else {
                $parent = $this->parent;
                $this->cate_id = $parent->cate_id;
                $this->level = $parent->level + 1;
                $this->tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id);
            }
        } else {
            // 修改父类
            if ($this->oldAttributes['pid'] != $this->pid) {
                $parent = $this->parent;
                $this->cate_id = $parent->cate_id;
                $level = $parent->level + 1;
                $tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id);
                // 查找所有子级
                $list = self::find()
                    ->where(['like', 'tree', $this->tree . TreeHelper::prefixTreeKey($this->id) . '%', false])
                    ->select(['id', 'level', 'tree'])
                    ->asArray()
                    ->all();

                /** @var Menu $item */
                foreach ($list as $item) {
                    $itemLevel = $item['level'] + ($level - $this->level);
                    $itemTree = str_replace($this->tree, $tree, $item['tree']);
                    self::updateAll(['cate_id' => $parent->cate_id, 'level' => $itemLevel, 'tree' => $itemTree], ['id' => $item['id']]);
                }

                $this->level = $level;
                $this->tree = $tree;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        self::deleteAll(['like', 'tree', $this->tree . TreeHelper::prefixTreeKey($this->id) . '%', false]);

        return parent::beforeDelete();
    }
}
