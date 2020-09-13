<?php

namespace common\widgets\selectlinkage;

use common\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use common\helpers\StringHelper;

/**
 * Class Linkage
 * @package common\widgets\selectlinkage
 * @author jianyan74 <751393839@qq.com>
 */
class Linkage extends InputWidget
{
    /**
     * 关联的ajax url
     *
     * @var
     */
    public $url;

    /**
     * 级别
     *
     * @var int
     */
    public $level = 3;

    /**
     * 默认值
     *
     * @var
     */
    public $item;

    /**
     * 所有值
     *
     * @var
     */
    public $allItem;

    /**
     * 模版
     *
     * @var string
     */
    public $template = 'linkage';

    /**
     * @return string
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        $col = 12 / $this->level;
        $width = (100 / $this->level) - 1;

        // 查找所有的上级
        $parents = ArrayHelper::getParents($this->allItem, $value);
        $parentIds = array_merge(array_column($parents, 'id'), [0]);
        // 默认可见的值
        $defaultItem = [];
        foreach ($this->allItem as $item) {
            if (in_array($item['pid'], $parentIds)) {
                !isset($defaultItem[$item['level']]) && $defaultItem[$item['level']] = [];
                $defaultItem[$item['level']][] = $item;
            }
        }

        // 说明文字
        $text = [];
        foreach ($parents as $parent) {
            $text[] = $parent['title'];
        }

        $this->allItem = ArrayHelper::itemsMerge($this->allItem);

        return $this->render($this->template, [
            'url' => $this->url,
            'level' => $this->level,
            'item' => Json::encode($this->item),
            'allItem' => Json::encode($this->allItem),
            'defaultItem' => Json::encode($defaultItem),
            'col' => $col,
            'width' => $width,
            'text' => implode('/', $text),
            'parents' => Json::encode($parents),
            'name' => $name,
            'value' => $value,
            'boxId' => StringHelper::uuid('uniqid')
        ]);
    }
}