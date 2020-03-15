<?php

namespace common\traits;

use common\helpers\ArrayHelper;
use common\helpers\TreeHelper;
use common\models\member\Member;

/**
 * Trait Tree
 *
 * 注意：必须带有
 *
 * id、pid、level、tree 字段
 *
 * 选择使用
 *
 * '''php
 *     public function getParent()
 *     {
 *          return $this->hasOne(self::class, ['id' => 'pid']);
 *     }
 * '''
 *
 * @package common\traits
 */
trait Tree
{
    /**
     * 关联父级
     *
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
        // 处理上下级关系
        $this->autoUpdateTree();

        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        // 自动删除所有下级
        $this->autoDeleteTree();

        return parent::beforeDelete();
    }

    /**
     * 自动删除所有下级
     */
    protected function autoDeleteTree()
    {
        self::deleteAll(['like', 'tree', $this->tree . TreeHelper::prefixTreeKey($this->id) . '%', false]);
    }

    /**
     * 自动更新树
     *
     * @param bool $insert
     * @return bool
     */
    protected function autoUpdateTree()
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
                    ->select(['id', 'level', 'tree', 'pid'])
                    ->asArray()
                    ->all();

                $distanceLevel = $level - $this->level;
                // 递归修改
                $data = ArrayHelper::itemsMerge($list, $this->id);
                $this->recursionUpdate($data, $distanceLevel, $tree);

                $this->level = $level;
                $this->tree = $tree;
            }
        }
    }

    /**
     * 递归更新数据
     *
     * @param $data
     * @param $distanceLevel
     * @param $tree
     */
    protected function recursionUpdate($data, $distanceLevel, $tree)
    {
        $updateIds = [];
        $itemLevel = '';
        $itemTree = '';

        foreach ($data as $item) {
            $updateIds[] = $item['id'];
            empty($itemLevel) && $itemLevel = $item['level'] + $distanceLevel;
            empty($itemTree) && $itemTree = str_replace($this->tree, $tree, $item['tree']);
            !empty($item['-']) && $this->recursionUpdate($item['-'], $distanceLevel, $tree);

            unset($item);
        }

        !empty($updateIds) && self::updateAll(['level' => $itemLevel, 'tree' => $itemTree], ['in', 'id', $updateIds]);
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
        $tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id ?? 0);

        return [$level, $tree];
    }
}