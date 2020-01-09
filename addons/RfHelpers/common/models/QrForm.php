<?php

namespace addons\RfHelpers\common\models;

use yii\base\Model;

/**
 * Class QrForm
 * @package addons\RfHelpers\common\models
 * @author jianyan74 <751393839@qq.com>
 */
class QrForm extends Model
{
    public $content;
    public $margin = 10;
    public $foreground = '#000000';
    public $background = '#FFFFFF';
    public $logo;
    public $logo_size = 50;
    public $label = 'RageFrame';
    public $label_size = 14;
    public $label_location = 'center';
    public $error_correction_level = 'low';
    public $size = 150;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'size', 'foreground', 'background'], 'required'],
            [['label_size', 'size', 'margin', 'logo_size'], 'integer', 'min' => 0],
            [['content', 'label', 'error_correction_level', 'label_location', 'logo', 'foreground', 'background'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'content' => '文本内容',
            'size' => '大小',
            'margin' => '内边距',
            'logo' => 'Logo',
            'logo_size' => 'Logo大小',
            'error_correction_level' => '容错级别',
            'label' => '标签',
            'label_size' => '标签大小',
            'label_location' => '标签位置',
            'foreground' => '前景色',
            'background' => '背景色',
        ];
    }
}