<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2018
 * @package yii2-date-range
 * @version 1.7.0
 */

namespace kartik\daterange;

use kartik\base\InputWidget;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * An advanced date range picker input for Yii Framework 2 based on bootstrap-daterangepicker plugin.
 *
 * @see https://github.com/dangrossman/bootstrap-daterangepicker
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class DateRangePicker extends InputWidget
{
    /**
     * @var string the javascript callback to be passed to the plugin constructor. Note: a default value is set for
     * this property when you set [[hideInput]] to false, OR you set [[useWithAddon]] to `true` or [[autoUpdateOnInit]]
     * to `false`. If you set a value here it will override any auto-generated callbacks.
     */
    public $callback = null;

    /**
     * @var boolean whether to auto update the input on initialization. If set to `false`, this will auto set the
     * `pluginOptions['autoUpdateInput']` to `false`. A default [[callback]] will be auto-generated when this is set to
     * `false`.
     */
    public $autoUpdateOnInit = false;

    /**
     * @var boolean whether to hide the input (e.g. when you want to show the date range picker as a dropdown). If set
     * to `true`, the input will be hidden. The plugin will be initialized on a container element (default 'div'),
     * using the container template. A default `callback` will be setup in this case to display the selected range
     * value within the container.
     */
    public $hideInput = false;

    /**
     * @var boolean whether you are using the picker with a input group addon. You can set it to `true`, when
     * `hideInput` is false, and you wish to show the picker position more correctly at the input-group addon indicator.
     * A default `callback` will be generated in this case to generate the selected range value for the input.
     */
    public $useWithAddon = false;

    /**
     * @var boolean initialize all the list values set in `pluginOptions['ranges']` and convert all values to
     * `yii\web\JsExpression`
     */
    public $initRangeExpr = true;

    /**
     * @var boolean show a preset dropdown. If set to true, this will automatically generate a preset list of ranges
     * for selection. Setting this to true will also automatically set `initRangeExpr` to true.
     */
    public $presetDropdown = false;

    /**
     * @var string the markup for the calendar picker icon. If not set this will default to:
     * - `<i class="glyphicon glyphicon-calendar"></i>` if [[bsVersion]] is set to `3.x`
     * - `<i class="fas fa-calendar-alt"></i>` if [[bsVersion]] is set to `4.x`
     */
    public $pickerIcon;

    /**
     * @var array the HTML attributes for the container, if hideInput is set to true. The following special options
     * are recognized:
     * - `tag`: string, the HTML tag for rendering the container. Defaults to `div`.
     */
    public $containerOptions = [];

    /**
     * @var string the attribute name which you can set optionally to track changes to the range start value. One of
     * the following actions will be taken when this is set:
     *  - If using with model, an active hidden input will be automatically generated using this as an attribute name
     *     for the start value of the range.
     *  - If using without model, a normal hidden input will be automatically generated using this as an input name
     *     for the start value of the range.
     */
    public $startAttribute;

    /**
     * @var string the attribute name which you can set optionally to track changes to the range end value. One of
     * the following actions will be taken when this is set:
     *  - If using with model, an active hidden input will be automatically generated using this as an attribute name
     *     for the end value of the range.
     *  - If using without model, a normal hidden input will be automatically generated using this as an input name
     *     for the end value of the range.
     */
    public $endAttribute;

    /**
     * @var array the HTML attributes for the start input (applicable only if `startAttribute` is set). If using
     * without a model, you can set a start value here within the `value` property.
     */
    public $startInputOptions = [];

    /**
     * @var array the HTML attributes for the end input (applicable only if `endAttribute` is set).  If using
     * without a model, you can set an end value here within the `value` property.
     */
    public $endInputOptions = [];

    /**
     * @var array (DEPRECATED) the HTML attributes for the `span` element that displays the default value for a preset
     * dropdown. This property is not used any more and the [[containerTemplate]] setting directly can be used to
     * render the preset value input.
     */
    public $defaultPresetValueOptions = [];

    /**
     * @var string the template for rendering the container, when hideInput is set to `true`. The special tag `{input}`
     * will be replaced with the hidden form input. In addition, the element with css class `range-value` will be
     * replaced by the calculated plugin value. The special token `{value}` will be replaced with the value of the hidden
     * form input during initialization and the token `{pickerIcon}` will be replaced with the [[pickerIcon]] property.
     */
    public $containerTemplate = <<< HTML
        <div class="kv-drp-dropdown">
            <span class="left-ind">{pickerIcon}</span>
            <input type="text" readonly class="form-control range-value" value="{value}">
            <span class="right-ind"><b class="caret"></b></span>
        </div>
        {input}
HTML;

    /**
     * @var boolean whether to HTML encode the value
     */
    public $encodeValue = true;

    /**
     * @var array the HTML attributes for the form input
     */
    public $options = ['class' => 'form-control'];

    /**
     * @inheritdoc
     */
    public $pluginName = 'daterangepicker';

    /**
     * @var string locale language to be used for the plugin
     */
    protected $_localeLang = '';

    /**
     * @var string the pluginOptions format for the date time
     */
    protected $_format;

    /**
     * @var string the pluginOptions separator
     */
    protected $_separator;

    /**
     * @var string the generated input for start attribute when `startAttribute` has been set
     */
    protected $_startInput = '';

    /**
     * @var string the generated input for end attribute when `endAttribute` has been set
     */
    protected $_endInput = '';

    /**
     * Automatically convert the date format from PHP DateTime to Moment.js DateTime format as required by the
     * `bootstrap-daterangepicker` plugin.
     *
     * @see http://php.net/manual/en/function.date.php
     * @see http://momentjs.com/docs/#/parsing/string-format/
     *
     * @param string $format the PHP date format string
     *
     * @return string
     */
    protected static function convertDateFormat($format)
    {
        $conversions = [
            // meridian lowercase remains same
            // 'a' => 'a',
            // meridian uppercase remains same
            // 'A' => 'A',
            // second (with leading zeros)
            's' => 'ss',
            // minute (with leading zeros)
            'i' => 'mm',
            // hour in 12-hour format (no leading zeros)
            'g' => 'h',
            // hour in 12-hour format (with leading zeros)
            'h' => 'hh',
            // hour in 24-hour format (no leading zeros)
            'G' => 'H',
            // hour in 24-hour format (with leading zeros)
            'H' => 'HH',
            //  day of the week locale
            'w' => 'e',
            //  day of the week ISO
            'W' => 'E',
            // day of month (no leading zero)
            'j' => 'D',
            // day of month (two digit)
            'd' => 'DD',
            // day name short
            'D' => 'DDD',
            // day name long
            'l' => 'DDDD',
            // month of year (no leading zero)
            'n' => 'M',
            // month of year (two digit)
            'm' => 'MM',
            // month name short
            'M' => 'MMM',
            // month name long
            'F' => 'MMMM',
            // year (two digit)
            'y' => 'YY',
            // year (four digit)
            'Y' => 'YYYY',
            // unix timestamp
            'U' => 'X',
        ];
        return strtr($format, $conversions);
    }

    /**
     * Parses and returns a JsExpression
     *
     * @param string|JsExpression $value
     *
     * @return JsExpression
     */
    protected static function parseJsExpr($value)
    {
        return $value instanceof JsExpression ? $value : new JsExpression($value);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    public function run()
    {
        $this->initSettings();
        echo $this->renderInput();
    }

    /**
     * Registers the needed client assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        MomentAsset::register($view);
        $input = 'jQuery("#' . $this->options['id'] . '")';
        $id = $input;
        if ($this->hideInput) {
            $id = 'jQuery("#' . $this->containerOptions['id'] . '")';
        }
        if (!empty($this->_langFile)) {
            LanguageAsset::register($view)->js[] = $this->_langFile;
        }
        DateRangePickerAsset::register($view);
        $rangeJs = '';
        if (empty($this->callback)) {
            $val = "start.format('{$this->_format}') + '{$this->_separator}' + end.format('{$this->_format}')";
            if (ArrayHelper::getValue($this->pluginOptions, 'singleDatePicker', false)) {
                $val = "start.format('{$this->_format}')";
            }
            $rangeJs = $this->getRangeJs('start') . $this->getRangeJs('end');
            $change = "{$input}.val(val).trigger('change');{$rangeJs}";
            if ($this->presetDropdown) {
                $id = "{$id}.find('.kv-drp-dropdown')";
            }
            if ($this->hideInput) {
                $script = "var val={$val};{$id}.find('.range-value').val(val);{$change}";
            } elseif ($this->useWithAddon) {
                $id = "{$input}.closest('.input-group')";
                $script = "var val={$val};{$change}";
            } elseif (!$this->autoUpdateOnInit) {
                $script = "var val={$val};{$change}";
            } else {
                $this->registerPlugin($this->pluginName, $id);
                return;
            }
            $this->callback = "function(start,end,label){{$script}}";
        }
        $nowFrom = "moment().startOf('day').format('{$this->_format}')";
        $nowTo = "moment().format('{$this->_format}')";
        // parse input change correctly when range input value is cleared
        $js = <<< JS
{$input}.off('change.kvdrp').on('change.kvdrp', function() {
    var drp = {$id}.data('{$this->pluginName}'), fm, to;
    if ($(this).val() || !drp) {
        return;
    }
    fm = {$nowFrom} || '';
    to = {$nowTo} || '';
    drp.setStartDate(fm);
    drp.setEndDate(to);
    {$rangeJs}
});
JS;
        if ($this->presetDropdown && empty($this->value)) {
            $js .= "var val={$nowFrom}+'{$this->_separator}'+{$nowTo};{$id}.find('.range-value').val(val);";
        }
        $view->registerJs($js);
        $this->registerPlugin($this->pluginName, $id, null, $this->callback);
    }

    /**
     * Initializes widget settings
     *
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    protected function initSettings()
    {
        $isBs4 = $this->isBs4();
        $this->_msgCat = 'kvdrp';
        if (!isset($this->pickerIcon)) {
            $iconCss = $isBs4 ? 'fas fa-calendar-alt' : 'glyphicon glyphicon-calendar';
            $this->pickerIcon = Html::tag('i', '', ['class' => $iconCss]);
        }
        if (!isset($this->pluginOptions['cancelButtonClasses'])) {
            $this->pluginOptions['cancelButtonClasses'] = $isBs4 ? 'btn-secondary' : 'btn-default';
        }
        $this->initI18N(__DIR__);
        $this->initLocale();
        if ($this->convertFormat && isset($this->pluginOptions['locale']['format'])) {
            $this->pluginOptions['locale']['format'] = static::convertDateFormat(
                $this->pluginOptions['locale']['format']
            );
        }
        $locale = ArrayHelper::getValue($this->pluginOptions, 'locale', []);
        $this->_format = ArrayHelper::getValue($locale, 'format', 'YYYY-MM-DD');
        $this->_separator = ArrayHelper::getValue($locale, 'separator', ' - ');
        if (!empty($this->value)) {
            if ($this->encodeValue) {
                $this->value = Html::encode($this->value);
            }
            $dates = explode($this->_separator, $this->value);
            if (count($dates) > 1) {
                $this->pluginOptions['startDate'] = $dates[0];
                $this->pluginOptions['endDate'] = $dates[1];
                $this->initRangeValue('start', $dates[0]);
                $this->initRangeValue('end', $dates[1]);
            }
            if ($this->startAttribute && $this->endAttribute) {
                $start = $this->getRangeValue('start');
                $end = $this->getRangeValue('end');
                $this->value = $start . $this->_separator . $end;
                if ($this->hasModel()) {
                    $attr = $this->attribute;
                    $this->model->$attr = $this->value;
                }
                $this->pluginOptions['startDate'] = $start;
                $this->pluginOptions['endDate'] = $end;
            }
        }
        $this->containerTemplate = strtr($this->containerTemplate, [
            '{value}' => isset($this->value) ? $this->value : '',
            '{pickerIcon}' => $this->pickerIcon,
        ]);
        // Set `autoUpdateInput` to false for certain settings
        if (!$this->autoUpdateOnInit || $this->hideInput || $this->useWithAddon) {
            $this->pluginOptions['autoUpdateInput'] = false;
        }
        $this->_startInput = $this->getRangeInput('start');
        $this->_endInput = $this->getRangeInput('end');
        if (empty($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->options['id'] . '-container';
        }
        if (empty($this->containerOptions['class'])) {
            $css = $this->useWithAddon && !$this->presetDropdown && !$this->hideInput ? ' input-group' : '';
            $this->containerOptions['class'] = 'kv-drp-container' . $css;
        }
        $this->initRange();
        $this->registerAssets();
    }

    /**
     * Initialize locale settings
     * @throws \ReflectionException
     */
    protected function initLocale()
    {
        $this->setLanguage('');
        if (empty($this->_langFile)) {
            return;
        }
        $localeSettings = ArrayHelper::getValue($this->pluginOptions, 'locale', []);
        $localeSettings += [
            'applyLabel' => Yii::t('kvdrp', 'Apply'),
            'cancelLabel' => Yii::t('kvdrp', 'Cancel'),
            'fromLabel' => Yii::t('kvdrp', 'From'),
            'toLabel' => Yii::t('kvdrp', 'To'),
            'weekLabel' => Yii::t('kvdrp', 'W'),
            'customRangeLabel' => Yii::t('kvdrp', 'Custom Range'),
            'daysOfWeek' => new JsExpression('moment.weekdaysMin()'),
            'monthNames' => new JsExpression('moment.monthsShort()'),
            'firstDay' => new JsExpression('moment.localeData()._week.dow'),
        ];
        $this->pluginOptions['locale'] = $localeSettings;
    }

    /**
     * Initializes the pluginOptions range list
     * @throws InvalidConfigException
     */
    protected function initRange()
    {
        if (isset($dummyValidation)) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $msg = Yii::t('kvdrp', 'Select Date Range');
        }
        if ($this->presetDropdown) {
            $m = 'moment()';
            $this->initRangeExpr = $this->hideInput = true;
            $this->pluginOptions['opens'] = ArrayHelper::getValue($this->pluginOptions, 'opens', 'left');
            $beg = "{$m}.startOf('day')";
            $end = "{$m}.endOf('day')";
            $last = "{$m}.subtract(1, 'month')";
            $this->pluginOptions['ranges'] = [
                Yii::t('kvdrp', 'Today') => [$beg, $end],
                Yii::t('kvdrp', 'Yesterday') => ["{$beg}.subtract(1,'days')", "{$end}.subtract(1,'days')"],
                Yii::t('kvdrp', 'Last {n} Days', ['n' => 7]) => ["{$beg}.subtract(6, 'days')", $end],
                Yii::t('kvdrp', 'Last {n} Days', ['n' => 30]) => ["{$beg}.subtract(29, 'days')", $end],
                Yii::t('kvdrp', 'This Month') => ["{$m}.startOf('month')", "{$m}.endOf('month')"],
                Yii::t('kvdrp', 'Last Month') => ["{$last}.startOf('month')", "{$last}.endOf('month')"],
            ];
            if (empty($this->value)) {
                $this->pluginOptions['startDate'] = new JsExpression("{$m}.startOf('day')");
                $this->pluginOptions['endDate'] = new JsExpression($m);
            }
        }
        $opts = $this->pluginOptions;
        if (!$this->initRangeExpr || empty($opts['ranges']) || !is_array($opts['ranges'])) {
            return;
        }
        $range = [];
        foreach ($opts['ranges'] as $key => $value) {
            if (!is_array($value) || empty($value[0]) || empty($value[1])) {
                throw new InvalidConfigException(
                    "Invalid settings for pluginOptions['ranges']. Each range value must be a two element array."
                );
            }
            $range[$key] = [static::parseJsExpr($value[0]), static::parseJsExpr($value[1])];
        }
        $this->pluginOptions['ranges'] = $range;
    }

    /**
     * Renders the input
     *
     * @return string
     */
    protected function renderInput()
    {
        $append = $this->_startInput . $this->_endInput;
        if (!$this->hideInput) {
            return $this->getInput('textInput') . $append;
        }
        $content = str_replace('{input}', $this->getInput('hiddenInput') . $append, $this->containerTemplate);
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'div');
        return Html::tag($tag, $content, $this->containerOptions);
    }

    /**
     * Gets input options based on type
     *
     * @param string $type whether `start` or `end`
     *
     * @return array|mixed
     */
    protected function getInputOpts($type = '')
    {
        $opts = $type . 'InputOptions';
        return isset($this->$opts) && is_array($this->$opts) ? $this->$opts : [];
    }

    /**
     * Sets input options for a specific type
     *
     * @param string $type whether `start` or `end`
     * @param array $options the options to set
     */
    protected function setInputOpts($type = '', $options = [])
    {
        $opts = $type . 'InputOptions';
        if (property_exists($this, $opts)) {
            $this->$opts = $options;
        }
    }

    /**
     * Gets the range attribute value based on type
     *
     * @param string $type whether `start` or `end`
     *
     * @return mixed|string
     */
    protected function getRangeAttr($type = '')
    {
        $attr = $type . 'Attribute';
        return $type && isset($this->$attr) ? $this->$attr : '';
    }

    /**
     * Generates and returns the client script on date range change, when the start and end attributes are set
     *
     * @param string $type whether `start` or `end`
     *
     * @return string
     */
    protected function getRangeJs($type = '')
    {
        $rangeAttr = $this->getRangeAttr($type);
        if (empty($rangeAttr)) {
            return '';
        }
        $options = $this->getInputOpts($type);
        $input = "jQuery('#" . $this->options['id'] . "')";
        return "var v={$input}.val() ? {$type}.format('{$this->_format}') : '';jQuery('#" . $options['id'] .
            "').val(v).trigger('change');";
    }

    /**
     * Generates and returns the hidden input markup when one of start or end attributes are set.
     *
     * @param string $type whether `start` or `end`
     *
     * @return string
     */
    protected function getRangeInput($type = '')
    {
        $attr = $this->getRangeAttr($type);
        if (empty($attr)) {
            return '';
        }
        $options = $this->getInputOpts($type);
        if (empty($options['id'])) {
            $options['id'] = $this->options['id'] . '-' . $type;
        }
        if ($this->hasModel()) {
            $this->setInputOpts($type, $options);
            return Html::activeHiddenInput($this->model, $attr, $options);
        }
        $options['type'] = 'hidden';
        $options['name'] = $attr;
        $this->setInputOpts($type, $options);
        return Html::tag('input', '', $options);
    }

    /**
     * Initializes the range values when one of start or end attributes are set.
     *
     * @param string $type whether `start` or `end`
     * @param string $value the value to set
     */
    protected function initRangeValue($type = '', $value = '')
    {
        $attr = $this->getRangeAttr($type);
        if (empty($attr) || empty($value)) {
            return;
        }
        if ($this->hasModel()) {
            $this->model->$attr = $value;
        } else {
            $options = $this->getInputOpts($type);
            $options['value'] = $value;
            $this->setInputOpts($type, $options);
        }
    }

    /**
     * Generates and returns the hidden input markup when one of start or end attributes are set.
     *
     * @param string $type whether `start` or `end`
     *
     * @return string
     */
    protected function getRangeValue($type = '')
    {
        $attr = $this->getRangeAttr($type);
        if (empty($attr)) {
            return '';
        }
        $options = $this->getInputOpts($type);
        return $this->hasModel() ? Html::getAttributeValue($this->model, $attr) :
            ArrayHelper::getValue($options, 'value', '');
    }
}
