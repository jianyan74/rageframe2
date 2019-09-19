<?php

namespace backend\widgets\jstree;

use yii\helpers\Json;
use yii\widgets\InputWidget;
use backend\widgets\jstree\assets\AppAsset;

/**
 * Class JsTree
 *
 * ```php
 *
 * $defaultData = [
 *    [
 *      'id' => 1,
 *      'pid' => 0,
 *      'title' => '测试1',
 *    ],
 *    [
 *      'id' => 2,
 *      'pid' => 0,
 *      'title' => '测试2',
 *    ],
 *    [
 *      'id' => 3,
 *      'pid' => 0,
 *      'title' => '测试3',
 *    ],
 *    [
 *      'id' => 4,
 *      'pid' => 1,
 *      'title' => '测试4',
 *    ],
 * ];
 *
 * $selectIds = [1, 2];
 *
 * ```
 *
 * @package backend\widgets\jstree
 * @author jianyan74 <751393839@qq.com>
 */
class JsTree extends InputWidget
{
    /**
     * ID
     *
     * @var
     */
    public $name;
    /**
     * @var string
     */
    public $theme = 'default';
    /**
     * 默认数据
     *
     * @var array
     */
    public $defaultData = [];
    /**
     * 选择的ID
     *
     * @var array
     */
    public $selectIds = [];
    /**
     * 过滤掉的ID
     *
     * @var array
     */
    protected $filtrationId = [];

    /**
     * @return string
     */
    public function run()
    {
        $this->registerClientScript();

        $defaultData = $this->defaultData;
        $selectIds = $this->selectIds;
        // 获取下级没有全部选择的ID
        $this->filtration(self::itemsMerge($defaultData));

        $jsTreeData = [];
        foreach ($defaultData as $datum) {
            $data = [
                'id' => $datum['id'],
                'parent' => !empty($datum['pid']) ? $datum['pid'] : '#',
                'text' => $datum['title'],
                // 'icon' => 'none'
            ];

            $jsTreeData[] = $data;
        }

        // 过滤选择的ID
        foreach ($selectIds as $key => $selectId) {
            if (in_array($selectId, $this->filtrationId)) {
                unset($selectIds[$key]);
            }
        }

        return $this->render($this->theme, [
            'name' => $this->name,
            'selectIds' => Json::encode(array_values($selectIds)),
            'defaultData' => Json::encode($jsTreeData),
        ]);
    }

    /**
     * 过滤
     *
     * @param $data
     */
    public function filtration($data)
    {
        foreach ($data as $datum) {
            if (!empty($datum['-'])) {
                $this->filtration($datum['-']);

                if (in_array($datum['id'], $this->selectIds)) {
                    $ids = array_column($datum['-'], 'id');
                    $selectChildIds = array_intersect($this->selectIds, $ids);

                    if (count($selectChildIds) != count($ids)) {
                        $this->filtrationId[] = $datum['id'];
                    }
                }
            }
        }
    }

    /**
     * 递归
     *
     * @param array $items
     * @param int $pid
     * @param string $idField
     * @param string $pidField
     * @param string $child
     * @return array
     */
    protected static function itemsMerge(array $items, $pid = 0, $idField = "id", $pidField = 'pid', $child = '-')
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v[$pidField] === $pid) {
                $v[$child] = self::itemsMerge($items, $v[$idField], $idField, $pidField);
                $arr[] = $v;
            }
        }

        return $arr;
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        AppAsset::register($view);
    }
}