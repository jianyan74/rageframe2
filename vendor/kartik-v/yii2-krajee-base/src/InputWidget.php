<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version   2.0.5
 */

namespace kartik\base;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget as YiiInputWidget;

/**
 * InputWidget is the base class for widgets extending the [[YiiInputWidget]] that collect user inputs in all
 * Krajee input extensions.
 *
 * An input widget can be associated with a data model and an attribute, or a name and a value. If the former, the
 * name and the value will be generated automatically.
 *
 * Classes extending from this widget can be used in an [[\yii\widgets\ActiveForm|ActiveForm]] using the
 * [[\yii\widgets\ActiveField::widget()|widget()]] method. For example like this:
 *
 * ```php
 * <?= $form->field($model, 'from_date')->widget('WidgetClassName', [
 *     // configure additional widget properties here
 * ]) ?>
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class InputWidget extends YiiInputWidget implements BootstrapInterface
{
    use TranslationTrait;
    use WidgetTrait;

    /**
     * @var string the HTML markup for widget loading indicator
     */
    const LOAD_PROGRESS = '<div class="kv-plugin-loading">&nbsp;</div>';

    /**
     * @var string the language configuration (e.g. 'fr-FR', 'zh-CN'). The format for the language/locale is
     * ll-CC where ll is a two or three letter lowercase code for a language according to ISO-639 and
     * CC is the country code according to ISO-3166.
     * If this property not set, then the current application language will be used.
     */
    public $language;

    /**
     * @var boolean whether input is to be disabled
     */
    public $disabled = false;

    /**
     * @var boolean whether input is to be readonly
     */
    public $readonly = false;

    /**
     * @var boolean show loading indicator while plugin loads
     */
    public $pluginLoading = true;

    /**
     * @var array the data (for list inputs)
     */
    public $data = [];

    /**
     * @var boolean whether the widget should automatically format the date from the PHP DateTime format to the
     * javascript/jquery plugin format. This is more applicable for widgets that manage date / time inputs.
     *
     * @see http://php.net/manual/en/function.date.php
     */
    public $convertFormat = false;

    /**
     * @var string the indicator to be displayed while plugin is loading.
     */
    protected $_loadIndicator = '';

    /**
     * @var string the two or three letter lowercase code for the language according to ISO-639.
     */
    protected $_lang = '';

    /**
     * @var string the language js file.
     */
    protected $_langFile = '';

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     * @throws \ReflectionException
     */
    public function init()
    {
        $this->initBsVersion();
        parent::init();
        $this->mergeDefaultOptions();
        $this->initDestroyJs();
        $this->initInputWidget();
    }

    /**
     * Initializes the input widget.
     * @throws \ReflectionException
     */
    protected function initInputWidget()
    {
        $this->initI18N(__DIR__, 'kvbase');
        if (!isset($this->language)) {
            $this->language = Yii::$app->language;
        }
        $this->_lang = Config::getLang($this->language);
        if ($this->pluginLoading) {
            $this->_loadIndicator = self::LOAD_PROGRESS;
        }
        if ($this->hasModel()) {
            $this->name = !isset($this->options['name']) ? Html::getInputName($this->model,
                $this->attribute) : $this->options['name'];
            $this->value = !isset($this->options['value']) ? Html::getAttributeValue($this->model,
                $this->attribute) : $this->options['value'];
        }
        $this->initDisability($this->options);
    }

    /**
     * Validates and sets disabled or readonly inputs.
     *
     * @param array $options the HTML attributes for the input
     */
    protected function initDisability(&$options)
    {
        if ($this->disabled && !isset($options['disabled'])) {
            $options['disabled'] = true;
        }
        if ($this->readonly && !isset($options['readonly'])) {
            $options['readonly'] = true;
        }
    }

    /**
     * Initialize the plugin language.
     *
     * @param string $property the name of language property in [[pluginOptions]].
     * @param boolean $full whether to use the full language string. Defaults to `false`
     * which is the 2 (or 3) digit ISO-639 format.
     * Defaults to 'language'.
     */
    protected function initLanguage($property = 'language', $full = false)
    {
        if (empty($this->pluginOptions[$property])) {
            $this->pluginOptions[$property] = $full ? $this->language : $this->_lang;
        }
    }

    /**
     * Sets the language JS file if it exists.
     *
     * @param string $prefix the language filename prefix
     * @param string $assetPath the path to the assets
     * @param string $filePath the path to the JS file with the file name prefix
     * @param string $suffix the file name suffix - defaults to '.js'
     * @throws \ReflectionException
     */
    protected function setLanguage($prefix, $assetPath = null, $filePath = null, $suffix = '.js')
    {
        $pwd = Config::getCurrentDir($this);
        $s = DIRECTORY_SEPARATOR;
        if ($assetPath === null) {
            $assetPath = "{$pwd}{$s}assets{$s}";
        } elseif (substr($assetPath, -1) != $s) {
            $assetPath .= $s;
        }
        if ($filePath === null) {
            $filePath = "js{$s}locales{$s}";
        } elseif (substr($filePath, -1) != $s) {
            $filePath .= $s;
        }
        $full = $filePath . $prefix . $this->language . $suffix;
        $fullLower = $filePath . $prefix . strtolower($this->language) . $suffix;
        $short = $filePath . $prefix . $this->_lang . $suffix;
        if (Config::fileExists($assetPath . $full)) {
            $this->_langFile = $full;
            $this->pluginOptions['language'] = $this->language;
        } elseif (Config::fileExists($assetPath . $fullLower)) {
            $this->_langFile = $fullLower;
            $this->pluginOptions['language'] = strtolower($this->language);
        } elseif (Config::fileExists($assetPath . $short)) {
            $this->_langFile = $short;
            $this->pluginOptions['language'] = $this->_lang;
        } else {
            $this->_langFile = '';
        }
        $this->_langFile = str_replace($s, '/', $this->_langFile);
    }

    /**
     * Generates an input.
     *
     * @param string $type the input type
     * @param boolean $list whether the input is of dropdown list type
     *
     * @return string the rendered input markup
     */
    protected function getInput($type, $list = false)
    {
        if ($this->hasModel()) {
            $input = 'active' . ucfirst($type);
            return $list ?
                Html::$input($this->model, $this->attribute, $this->data, $this->options) :
                Html::$input($this->model, $this->attribute, $this->options);
        }
        $input = $type;
        $checked = false;
        if ($type == 'radio' || $type == 'checkbox') {
            $checked = ArrayHelper::remove($this->options, 'checked', '');
            if (empty($checked) && !empty($this->value)) {
                $checked = ($this->value == 0) ? false : true;
            } elseif (empty($checked)) {
                $checked = false;
            }
        }
        return $list ?
            Html::$input($this->name, $this->value, $this->data, $this->options) :
            (($type == 'checkbox' || $type == 'radio') ?
                Html::$input($this->name, $checked, $this->options) :
                Html::$input($this->name, $this->value, $this->options));
    }

    /**
     * Automatically convert the date format from PHP DateTime to Javascript DateTime format
     *
     * @see http://php.net/manual/en/function.date.php
     * @see http://bootstrap-datetimepicker.readthedocs.org/en/release/options.html#format
     *
     * @param string $format the PHP date format string
     *
     * @return string
     */
    protected static function convertDateFormat($format)
    {
        return strtr($format, [
            // meridian lowercase
            'a' => 'p',
            // meridian uppercase
            'A' => 'P',
            // second (with leading zeros)
            's' => 'ss',
            // minute (with leading zeros)
            'i' => 'ii',
            // hour in 12-hour format (no leading zeros)
            'g' => 'H',
            // hour in 24-hour format (no leading zeros)
            'G' => 'h',
            // hour in 12-hour format (with leading zeros)
            'h' => 'HH',
            // hour in 24-hour format (with leading zeros)
            'H' => 'hh',
            // day of month (no leading zero)
            'j' => 'd',
            // day of month (two digit)
            'd' => 'dd',
            // day name short is always 'D'
            // day name long
            'l' => 'DD',
            // month of year (no leading zero)
            'n' => 'm',
            // month of year (two digit)
            'm' => 'mm',
            // month name short is always 'M'
            // month name long
            'F' => 'MM',
            // year (two digit)
            'y' => 'yy',
            // year (four digit)
            'Y' => 'yyyy',
        ]);
    }

    /**
     * Parses and sets plugin date format based on attribute type using [[FormatConverter]]. Currently this method is
     * used only within the [[\kartik\date\DatePicker]] and [[\kartik\datetime\DateTimePicker\]] widgets.
     *
     * @param string $type the attribute type whether date, datetime, or time.
     *
     * @throws InvalidConfigException
     */
    protected function parseDateFormat($type)
    {
        if (!$this->convertFormat) {
            return;
        }
        if (isset($this->pluginOptions['format'])) {
            $format = $this->pluginOptions['format'];
            $format = strncmp($format, 'php:', 4) === 0 ? substr($format, 4) :
                FormatConverter::convertDateIcuToPhp($format, $type);
            $this->pluginOptions['format'] = static::convertDateFormat($format);
            return;
        }
        $attrib = $type . 'Format';
        $format = isset(Yii::$app->formatter->$attrib) ? Yii::$app->formatter->$attrib : '';
        if (empty($format)) {
            throw new InvalidConfigException("Error parsing '{$type}' format.");
        }
        $format = strncmp($format, 'php:', 4) === 0 ? substr($format, 4) :
            FormatConverter::convertDateIcuToPhp($format, $type);
        $this->pluginOptions['format'] = static::convertDateFormat($format);
    }
}
