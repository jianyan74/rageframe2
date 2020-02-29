<?php

namespace common\models\common;

use Yii;
use common\helpers\ArrayHelper;
use common\helpers\TreeHelper;

/**
 * This is the model class for table "rf_common_menu".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $app_id 应用
 * @property int $is_addon 是否插件
 * @property string $addons_name 插件名称
 * @property int $cate_id 分类id
 * @property string $pid 上级id
 * @property string $url 路由
 * @property string $icon 样式
 * @property int $level 级别
 * @property int $dev 开发者[0:都可见;开发模式可见]
 * @property int $sort 排序
 * @property array $params 参数
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $tree 树
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
        return '{{%common_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'pid', 'cate_id'], 'required'],
            [['cate_id', 'pid', 'level', 'is_addon', 'dev', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'safe'],
            [['title', 'icon'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['addons_name', 'url'], 'string', 'max' => 100],
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
            'app_id' => '应用',
            'is_addon' => '是否插件',
            'addons_name' => '插件名称',
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
     * @param Menu $parent
     */
    public function setParent(Menu $parent)
    {
        $this->parent = $parent;
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

            !$this->app_id && $this->app_id = $this->cate->app_id;
            !$this->addons_name && $this->addons_name = $this->cate->addons_name;
            !$this->is_addon && $this->is_addon = $this->cate->is_addon;
        } else {
            // 修改父类
            if ($this->oldAttributes['pid'] != $this->pid) {
                $parent = $this->parent;
                if ($this->pid == 0) {
                    $parent = new self();
                    $parent = $parent->loadDefaultValues();
                    $parent->cate_id = $this->cate_id;
                }

                $this->cate_id = $parent->cate_id;
                $level = $parent->level + 1;
                $tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id ?? 0);
                // 查找所有子级
                $list = Yii::$app->services->menu->findChildByID($this->tree, $this->id);

                $distanceLevel = $level - $this->level;
                // 递归修改
                $data = ArrayHelper::itemsMerge($list, $this->id);
                $this->recursionUpdate($data, $parent->cate_id, $distanceLevel, $tree);

                $this->level = $level;
                $this->tree = $tree;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * 递归更新数据
     *
     * @param $data
     * @param $distanceLevel
     * @param $tree
     */
    protected function recursionUpdate($data, $cate_id, $distanceLevel, $tree)
    {
        $updateIds = [];
        $itemLevel = '';
        $itemTree = '';

        foreach ($data as $item) {
            $updateIds[] = $item['id'];
            empty($itemLevel) && $itemLevel = $item['level'] + $distanceLevel;
            empty($itemTree) && $itemTree = str_replace($this->tree, $tree, $item['tree']);
            !empty($item['-']) && $this->recursionUpdate($item['-'], $cate_id, $distanceLevel, $tree);

            unset($item);
        }

        !empty($updateIds) && self::updateAll(['cate_id' => $cate_id, 'level' => $itemLevel, 'tree' => $itemTree],
            ['in', 'id', $updateIds]);
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
