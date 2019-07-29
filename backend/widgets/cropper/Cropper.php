<?php

namespace backend\widgets\cropper;

use yii\helpers\Url;
use yii\widgets\InputWidget;
use common\helpers\ArrayHelper;
use common\helpers\Html;
use common\helpers\StringHelper;
use common\models\common\Attachment;

/**
 * Class Cropper
 * @package backend\widgets\cropper
 * @author jianyan74 <751393839@qq.com>
 */
class Cropper extends InputWidget
{
    /**
     * @var array
     */
    public $config = [];

    /**
     * @var array
     */
    public $formData = [];

    /**
     * 默认主题
     *
     * @var string
     */
    public $theme = 'default';

    /**
     * 默认主题配置
     *
     * @var array
     */
    public $themeConfig = [];

    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $this->boxId = md5($this->name) . StringHelper::uuid('uniqid');
        $this->config = ArrayHelper::merge([
            'aspectRatio' => '1',
            'multiple' => false,
            'server' => Url::to(['/file/base64']),
        ], $this->config);

        $this->formData = ArrayHelper::merge([
            'drive' => Attachment::DRIVE_LOCAL,
            'image' => '',
        ], $this->formData);

        $this->themeConfig = ArrayHelper::merge([
            'select' => true
        ], $this->themeConfig);
    }

    /**
     * @return string
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        empty($value) && $value = [];
        if ($this->config['multiple'] == true) {
            // 赋予默认值
            $name = $name . '[]';

            if ($value && !is_array($value)) {
                $value = json_decode($value, true);
                empty($value) && $value = unserialize($value);
                empty($value) && $value = [];
            }
        }

        if (!is_array($value)) {
            $tmp = $value;
            $value = [];
            $value[] = $tmp;
        }

        return $this->render($this->theme, [
            'name' => $name,
            'value' => $value,
            'boxId' => $this->boxId,
            'config' => $this->config,
            'type' => 'images',
            'formData' => $this->formData,
            'themeConfig' => $this->themeConfig,
        ]);
    }
}