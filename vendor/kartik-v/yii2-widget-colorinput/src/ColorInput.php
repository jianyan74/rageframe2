<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2018
 * @package yii2-widgets
 * @subpackage yii2-widget-colorinput
 * @version 1.0.5
 */

namespace kartik\color;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;
use kartik\base\Html5Input;
use kartik\base\Html5InputAsset;
use kartik\base\TranslationTrait;

/**
 * ColorInput widget is an enhanced widget encapsulating the HTML 5 color input.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 * @see http://twitter.github.com/typeahead.js/examples
 */
class ColorInput extends Html5Input
{
    /**
     * @var boolean whether to use the native HTML5 color input
     */
    public $useNative = false;

    /**
     * @var boolean whether to show a default palette of colors
     */
    public $showDefaultPalette = true;

    /**
     * @var string the name of the jQuery plugin
     */
    public $pluginName = 'spectrum';

    /**
     * @var array default plugin options
     */
    protected $_defaultOptions = [
        'showInput' => true,
        'showInitial' => true,
        'showPalette' => true,
        'showSelectionPalette' => true,
        'showAlpha' => true,
        'allowEmpty' => true,
        'preferredFormat' => 'hex',
        'theme' => 'sp-krajee'
    ];

    /**
     * @var array default palette settings
     */
    protected $_defaultPalette = [
        [
            "rgb(0, 0, 0)",
            "rgb(67, 67, 67)",
            "rgb(102, 102, 102)",
            "rgb(204, 204, 204)",
            "rgb(217, 217, 217)",
            "rgb(255, 255, 255)"
        ],
        [
            "rgb(152, 0, 0)",
            "rgb(255, 0, 0)",
            "rgb(255, 153, 0)",
            "rgb(255, 255, 0)",
            "rgb(0, 255, 0)",
            "rgb(0, 255, 255)",
            "rgb(74, 134, 232)",
            "rgb(0, 0, 255)",
            "rgb(153, 0, 255)",
            "rgb(255, 0, 255)"
        ],
        [
            "rgb(230, 184, 175)",
            "rgb(244, 204, 204)",
            "rgb(252, 229, 205)",
            "rgb(255, 242, 204)",
            "rgb(217, 234, 211)",
            "rgb(208, 224, 227)",
            "rgb(201, 218, 248)",
            "rgb(207, 226, 243)",
            "rgb(217, 210, 233)",
            "rgb(234, 209, 220)"
        ],
        [
            "rgb(221, 126, 107)",
            "rgb(234, 153, 153)",
            "rgb(249, 203, 156)",
            "rgb(255, 229, 153)",
            "rgb(182, 215, 168)",
            "rgb(162, 196, 201)",
            "rgb(164, 194, 244)",
            "rgb(159, 197, 232)",
            "rgb(180, 167, 214)",
            "rgb(213, 166, 189)"
        ],
        [
            "rgb(204, 65, 37)",
            "rgb(224, 102, 102)",
            "rgb(246, 178, 107)",
            "rgb(255, 217, 102)",
            "rgb(147, 196, 125)",
            "rgb(118, 165, 175)",
            "rgb(109, 158, 235)",
            "rgb(111, 168, 220)",
            "rgb(142, 124, 195)",
            "rgb(194, 123, 160)"
        ],
        [
            "rgb(166, 28, 0)",
            "rgb(204, 0, 0)",
            "rgb(230, 145, 56)",
            "rgb(241, 194, 50)",
            "rgb(106, 168, 79)",
            "rgb(69, 129, 142)",
            "rgb(60, 120, 216)",
            "rgb(61, 133, 198)",
            "rgb(103, 78, 167)",
            "rgb(166, 77, 121)"
        ],
        [
            "rgb(91, 15, 0)",
            "rgb(102, 0, 0)",
            "rgb(120, 63, 4)",
            "rgb(127, 96, 0)",
            "rgb(39, 78, 19)",
            "rgb(12, 52, 61)",
            "rgb(28, 69, 135)",
            "rgb(7, 55, 99)",
            "rgb(32, 18, 77)",
            "rgb(76, 17, 48)"
        ]
    ];

    /**
     * @var string the javascript variable that will store the default palette settings.
     */
    protected $_paletteVar;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->_msgCat = 'kvcolor';
        if (!isset($this->type)) {
            $this->type = $this->useNative ? 'color' : 'text';
        }
        $this->initI18N(__DIR__);
        if (empty($this->html5Container['id'])) {
            $this->html5Container['id'] = $this->options['id'] . '-cont';
        }
        if ($this->type === 'text') {
            Html::addCssStyle($this->html5Options, 'display:none');
            if ($this->pluginLoading) {
                Html::addCssClass($this->html5Container, 'kv-center-loading');
            }
        }
        $this->html5Options['value'] = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        if (substr($this->language, 0, 2) !== 'en') {
            $this->_defaultOptions += [
                'cancelText' => Yii::t('kvcolor', 'cancel'),
                'chooseText' => Yii::t('kvcolor', 'choose'),
                'clearText' => Yii::t('kvcolor', 'Clear Color Selection'),
                'noColorSelectedText' => Yii::t('kvcolor', 'No Color Selected'),
                'togglePaletteMoreText' => Yii::t('kvcolor', 'more'),
                'togglePaletteLessText' => Yii::t('kvcolor', 'less'),
            ];
        }
        Html::addCssClass($this->containerOptions, 'spectrum-group');
        Html::addCssClass($this->html5Options, 'spectrum-source');
        Html::addCssClass($this->options, 'spectrum-input');
        if (!$this->useNative) {
            Html::addCssClass($this->html5Container, 'input-group-sp');
            $this->pluginOptions = array_replace_recursive($this->_defaultOptions, $this->pluginOptions);
        }
        $this->initInput();
        $this->registerColorInput();
    }

    /**
     * Registers the needed assets
     */
    public function registerColorInput()
    {
        $view = $this->getView();
        ColorInputAsset::register($view);
        if ($this->useNative) {
            return;
        }
        if ($this->showDefaultPalette) {
            $palette = Json::encode($this->_defaultPalette);
            $this->_paletteVar = 'kvPalette_' . hash('crc32', $palette);
            $view->registerJs("var {$this->_paletteVar}={$palette};", View::POS_HEAD);
            $this->pluginOptions['palette'] = new JsExpression($this->_paletteVar);
        }
        Html5InputAsset::register($view);
        $input = 'jQuery("#' . $this->html5Options['id'] . '")';
        $el = 'jQuery("#' . $this->options['id'] . '")';
        $cont = 'jQuery("#' . $this->html5Container['id'] . '")';
        $doneJs = "function(){{$input}.spectrum('set',{$el}.val());{$cont}.removeClass('kv-center-loading')}";
        $this->registerPlugin($this->pluginName, $input, $doneJs);
    }
}
