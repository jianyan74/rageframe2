<?php
namespace backend\widgets\color;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use backend\widgets\color\assets\AppAsset;
use common\helpers\StringHelper;

/**
 * 颜色选择器
 *
 * Class Color
 * @package backend\widgets\color
 * @author kbdxbt
 */
class Color extends InputWidget
{
    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
        $this->boxId = md5($this->name) . StringHelper::uuid('uniqid');

        // 注册资源
        $this->registerClientScript();

        return $this->render('color', [
            'name' => $name,
            'value' => $value,
            'boxId' => $this->boxId
        ]);
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