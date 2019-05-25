<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version   2.0.5
 */

namespace kartik\base;

use Yii;
use ReflectionClass;
use yii\base\InvalidConfigException;

/**
 * Global configuration helper class for Krajee extensions.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class Config
{
    /**
     * @var string the Krajee repo vendor name
     */
    const VENDOR_NAME = 'kartik-v/';

    /**
     * @var string the Krajee extension namespace
     */
    const NAMESPACE_PREFIX = '\\kartik\\';

    /**
     * @var string the default reason appended for exceptions
     */
    const DEFAULT_REASON = 'for your selected functionality';

    /**
     * @var array list of valid html inputs
     */
    protected static $_validHtmlInputs = [
        'hiddenInput',
        'textInput',
        'passwordInput',
        'textArea',
        'checkbox',
        'radio',
        'listBox',
        'dropDownList',
        'checkboxList',
        'radioList',
        'input',
        'fileInput',
    ];

    /**
     * @var array list of valid dropdown inputs
     */
    protected static $_validDropdownInputs = [
        'listBox',
        'dropDownList',
        'checkboxList',
        'radioList',
        'checkboxButtonGroup',
        'radioButtonGroup',
    ];

    /**
     * @var array list of valid Krajee input widgets
     */
    protected static $_validInputWidgets = [
        '\kartik\typeahead\Typeahead' => ['yii2-widgets', 'yii2-widget-typeahead'],
        '\kartik\select2\Select2' => ['yii2-widgets', 'yii2-widget-select2'],
        '\kartik\depdrop\DepDrop' => ['yii2-widgets', 'yii2-widget-depdrop'],
        '\kartik\touchspin\TouchSpin' => ['yii2-widgets', 'yii2-widget-touchspin'],
        '\kartik\switchinput\SwitchInput' => ['yii2-widgets', 'yii2-widget-switchinput'],
        '\kartik\rating\StarRating' => ['yii2-widgets', 'yii2-widget-rating'],
        '\kartik\file\FileInput' => ['yii2-widgets', 'yii2-widget-fileinput'],
        '\kartik\range\RangeInput' => ['yii2-widgets', 'yii2-widget-rangeinput'],
        '\kartik\color\ColorInput' => ['yii2-widgets', 'yii2-widget-colorinput'],
        '\kartik\date\DatePicker' => ['yii2-widgets', 'yii2-widget-datepicker'],
        '\kartik\time\TimePicker' => ['yii2-widgets', 'yii2-widget-timepicker'],
        '\kartik\datetime\DateTimePicker' => ['yii2-widgets', 'yii2-widget-datetimepicker'],
        '\kartik\daterange\DateRangePicker' => 'yii2-date-range',
        '\kartik\sortinput\SortableInput' => 'yii2-sortinput',
        '\kartik\tree\TreeViewInput' => 'yii2-tree-manager',
        '\kartik\money\MaskMoney' => 'yii2-money', // deprecated and replaced by yii2-number
        '\kartik\number\NumberControl' => 'yii2-number',
        '\kartik\checkbox\CheckboxX' => 'yii2-checkbox-x',
        '\kartik\slider\Slider' => 'yii2-slider',
    ];

    /**
     * Validate multiple extension dependencies.
     *
     * @param array $extensions the configuration of extensions with each array item setup as required in
     * `checkDependency` method. The following keys can be setup:
     *
     * - `name`: _string_, the extension class name (without vendor namespace prefix)
     * - `repo`: _string_, the extension package repository name (without vendor name prefix)
     * - `reason`: _string_, a user friendly message for dependency validation failure
     *
     * @throws InvalidConfigException if extension fails dependency validation
     */
    public static function checkDependencies($extensions = [])
    {
        foreach ($extensions as $extension) {
            $name = empty($extension[0]) ? '' : $extension[0];
            $repo = empty($extension[1]) ? '' : $extension[1];
            $reason = empty($extension[2]) ? '' : self::DEFAULT_REASON;
            static::checkDependency($name, $repo, $reason);
        }
    }

    /**
     * Validate a single extension dependency
     *
     * @param string $name the extension class name (without vendor namespace prefix)
     * @param mixed $repo the extension package repository names (without vendor name prefix)
     * @param string $reason a user friendly message for dependency validation failure
     *
     * @throws InvalidConfigException if extension fails dependency validation
     */
    public static function checkDependency($name = '', $repo = '', $reason = self::DEFAULT_REASON)
    {
        if (empty($name)) {
            return;
        }
        $command = 'php composer.phar require ' . self::VENDOR_NAME;
        $version = ' \'@dev\'';
        $class = (substr($name, 0, 8) == self::NAMESPACE_PREFIX) ? $name : self::NAMESPACE_PREFIX . $name;

        if (is_array($repo)) {
            $repos = "one of '" . implode("' OR '", $repo) . "' extensions. ";
            $installs = $command . implode("{$version}\n\n--- OR ---\n\n{$command}", $repo) . $version;
        } else {
            $repos = "the '" . $repo . "' extension. ";
            $installs = $command . $repo . $version;
        }

        if (!class_exists($class)) {
            throw new InvalidConfigException(
                "The class '{$class}' was not found and is required {$reason}.\n\n" .
                "Please ensure you have installed {$repos}" .
                "To install, you can run this console command from your application root:\n\n{$installs}"
            );
        }
    }

    /**
     * Gets list of namespaced Krajee input widget classes as an associative array, where the array keys are the
     * namespaced classes, and the array values are the names of the github repository to which these classes belong to.
     *
     * @return array
     */
    public static function getInputWidgets()
    {
        return static::$_validInputWidgets;
    }

    /**
     * Check if a type of input is any possible valid input (html or widget)
     *
     * @param string $type the type of input
     *
     * @return boolean
     */
    public static function isValidInput($type)
    {
        return static::isHtmlInput($type) || static::isInputWidget($type) || $type === 'widget';
    }

    /**
     * Check if a input type is a valid Html Input
     *
     * @param string $type the type of input
     *
     * @return boolean
     */
    public static function isHtmlInput($type)
    {
        return in_array($type, static::$_validHtmlInputs);
    }

    /**
     * Check if a type of input is a valid input widget
     *
     * @param string $type the type of input
     *
     * @return boolean
     */
    public static function isInputWidget($type)
    {
        return isset(static::$_validInputWidgets[$type]);
    }

    /**
     * Check if a input type is a valid dropdown input
     *
     * @param string $type the type of input
     *
     * @return boolean
     */
    public static function isDropdownInput($type)
    {
        return in_array($type, static::$_validDropdownInputs);
    }

    /**
     * Check if a namespaced widget is valid or installed.
     *
     * @param string $type the widget type
     * @param string $reason the message to be displayed for dependency failure
     *
     * @throws InvalidConfigException
     */
    public static function validateInputWidget($type, $reason = self::DEFAULT_REASON)
    {
        if (static::isInputWidget($type)) {
            static::checkDependency($type, static::$_validInputWidgets[$type], $reason);
        }
    }

    /**
     * Convert a language string in yii\i18n format to a ISO-639 format (2 or 3 letter code).
     *
     * @param string $language the input language string
     *
     * @return string
     */
    public static function getLang($language)
    {
        $pos = strpos($language, '-');
        return $pos > 0 ? substr($language, 0, $pos) : $language;
    }

    /**
     * Get the current directory of the extended class object
     *
     * @param object $object the called object instance
     *
     * @return string
     * @throws \ReflectionException
     */
    public static function getCurrentDir($object)
    {
        if (empty($object)) {
            return '';
        }
        $child = new ReflectionClass($object);
        return dirname($child->getFileName());
    }

    /**
     * Check if a file exists
     *
     * @param string $file the file with path in URL format
     *
     * @return bool
     */
    public static function fileExists($file)
    {
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
        return file_exists($file);
    }

    /**
     * Initializes and validates the module (deprecated since v1.9.0 - use `getModule` instead directly)
     *
     * @param string $class the Module class name
     *
     * @return \yii\base\Module
     *
     * @throws InvalidConfigException
     */
    public static function initModule($class)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $m = $class::MODULE;
        $module = $m ? static::getModule($m) : null;
        if ($module === null || !$module instanceof $class) {
            throw new InvalidConfigException("The '{$m}' module MUST be setup in your Yii configuration file and must be an instance of '{$class}'.");
        }
        return $module;
    }

    /**
     * Gets the module instance by validating the module name. The check is first done for a submodule of the same name
     * and then the check is done for the module within the current Yii2 application.
     *
     * @param string $m the module identifier
     * @param string $class the module class name
     *
     * @throws InvalidConfigException
     *
     * @return yii\base\Module
     */
    public static function getModule($m, $class = '')
    {
        $app = Yii::$app;
        $mod = isset($app->controller) && $app->controller->module ? $app->controller->module : null;
        $module = null;
        if ($mod) {
            $module = $mod->id === $m ? $mod : $mod->getModule($m);
        }
        if (!$module) {
            $module = $app->getModule($m);
        }
        if ($module === null) {
            throw new InvalidConfigException("The '{$m}' module MUST be setup in your Yii configuration file.");
        }
        if (!empty($class) && !$module instanceof $class) {
            throw new InvalidConfigException("The '{$m}' module MUST be an instance of '{$class}'.");
        }
        return $module;
    }

    /**
     * Check if HTML options has specified CSS class
     * @param array $options the HTML options
     * @param string $cssClass the css class to test
     * @return bool
     */
    public static function hasCssClass($options, $cssClass)
    {
        if (!isset($options['class'])) {
            return false;
        }
        $classes = is_array($options['class']) ? $options['class'] :
            preg_split('/\s+/', $options['class'], -1, PREG_SPLIT_NO_EMPTY);
        return in_array($cssClass, $classes);
    }
}
