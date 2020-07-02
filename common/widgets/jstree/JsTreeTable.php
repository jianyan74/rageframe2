<?php

namespace common\widgets\jstree;

use yii\helpers\Json;
use yii\widgets\InputWidget;
use common\widgets\jstree\assets\AppAsset;

/**
 * Class JsTreeTable
 * @package common\widgets\jstree
 * @author jianyan74 <751393839@qq.com>
 */
class JsTreeTable extends InputWidget
{
    public $title = '节点管理';

    /**
     * ID
     *
     * @var
     */
    public $name;
    /**
     * @var string
     */
    public $theme = 'table';
    /**
     * 默认数据
     *
     * @var array
     */
    public $defaultData = [];
    /**
     * 编辑地址
     *
     * @var string
     */
    public $editUrl;
    /**
     * 删除地址
     *
     * @var string
     */
    public $deleteUrl;
    /**
     * 移动地址
     *
     * @var string
     */
    public $moveUrl;

    /**
     * @return string
     */
    public function run()
    {
        $this->registerClientScript();

        $defaultData = $this->defaultData;
        $jsTreeData = [];
        foreach ($defaultData as $datum) {
            $data = [
                'id' => $datum['id'],
                'parent' => !empty($datum['pid']) ? $datum['pid'] : '#',
                'text' => trim($datum['title']),
                 'icon' => 'glyphicon glyphicon-folder-close'
            ];

            $jsTreeData[] = $data;
        }

        return $this->render($this->theme, [
            'title' => $this->title,
            'name' => $this->name,
            'editUrl' => $this->editUrl,
            'deleteUrl' => $this->deleteUrl,
            'moveUrl' => $this->moveUrl,
            'defaultData' => Json::encode($jsTreeData),
        ]);
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
    protected static function itemsMerge(array $items, $pid = 0)
    {
        $arr = [];
        foreach ($items as $v) {
            if (is_numeric($pid)) {
                if ($v['pid'] == $pid) {
                    $v['-'] = self::itemsMerge($items, $v['id']);
                    $arr[] = $v;
                }
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