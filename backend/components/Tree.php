<?php
namespace backend\components;

use yii\db\ActiveRecord;
use common\helpers\TreeHelper;

/**
 * Trait Tree
 *
 * 注意：必须带有
 *
 * '''php
 *     public function getParent()
 *     {
 *          return $this->hasOne(self::class, ['id' => 'pid']);
 *     }
 * '''
 *
 * 和
 * id、pid、level、tree 字段
 * @package backend\components
 */
trait Tree
{
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
                list($level, $tree) = $this->getParentData();
                $this->level = $level;
                $this->tree = $tree;
            }
        } else {
            // 修改父类
            if ($this->oldAttributes['pid'] != $this->pid) {
                list($level, $tree) = $this->getParentData();
                // 查找所有子级
                $list = self::find()
                    ->where(['like', 'tree', $this->tree . TreeHelper::prefixTreeKey($this->id) . '%', false])
                    ->select(['id', 'level', 'tree'])
                    ->asArray()
                    ->all();

                /** @var ActiveRecord $item */
                foreach ($list as $item) {
                    $itemLevel = $item['level'] + ($level - $this->level);
                    $itemTree = str_replace($this->tree, $tree, $item['tree']);
                    self::updateAll(['level' => $itemLevel, 'tree' => $itemTree], ['id' => $item['id']]);
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

    /**
     * @return array
     */
    protected function getParentData()
    {
        if (!$parent = $this->parent) {
            return [1, TreeHelper::defaultTreeKey()];
        }

        $level = $parent->level + 1;
        $tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id);

        return [$level, $tree];
    }
}