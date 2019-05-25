<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version   2.0.5
 */

namespace kartik\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;

/**
 * BootstrapTrait includes bootstrap library init and parsing methods. The class which uses this trait, must also
 * necessarily implement the [[BootstrapInterface]].
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
trait BootstrapTrait
{
    /**
     * @var array CSS conversion mappings for BS3.x and BS4.x. This is set as `$key => $value` pairs where:
     * - `$key`: _string_, is the style type to be configured (one of the constants starting with `BS_`)
     * - `$value`: _array_, consists of 2 array items:
     *      - the first item represents the CSS class(es) for Bootstrap 3.x.
     *      - the second item represents the CSS class(es) for Bootstrap 4.x
     *   If more than one CSS class is to be applied - it is represented as a sub array of the relevant CSS classes.
     */
    public static $bsCssMap = [
        self::BS_PANEL => ['panel', 'card'],
        self::BS_PANEL_HEADING => ['panel-heading', 'card-header'],
        self::BS_PANEL_TITLE => ['panel-title', 'card-title'],
        self::BS_PANEL_BODY => ['panel-body', 'card-body'],
        self::BS_PANEL_FOOTER => ['panel-footer', 'card-footer'],
        self::BS_PANEL_DEFAULT => ['panel-default', ''],
        self::BS_PANEL_PRIMARY => ['panel-primary', ['bg-primary', 'text-white']],
        self::BS_PANEL_SUCCESS => ['panel-success', ['bg-success', 'text-white']],
        self::BS_PANEL_INFO => ['panel-info', ['bg-info', 'text-white']],
        self::BS_PANEL_WARNING => ['panel-warning', ['bg-warning', 'text-white']],
        self::BS_PANEL_DANGER => ['panel-danger', ['bg-danger', 'text-white']],
        self::BS_LABEL => ['label', 'badge'],
        self::BS_BADGE => ['badge', ['badge', 'badge-pill']],
        self::BS_LABEL_DEFAULT => ['label-default', 'badge-secondary'],
        self::BS_LABEL_PRIMARY => ['label-primary', 'badge-primary'],
        self::BS_LABEL_SUCCESS => ['label-success', 'badge-success'],
        self::BS_LABEL_INFO => ['label-info', 'badge-info'],
        self::BS_LABEL_WARNING => ['label-warning', 'badge-warning'],
        self::BS_LABEL_DANGER => ['label-danger', 'badge-danger'],
        self::BS_TABLE_ACTIVE => ['default', 'table-active'],
        self::BS_TABLE_PRIMARY => ['primary', 'table-primary'],
        self::BS_TABLE_SUCCESS => ['success', 'table-success'],
        self::BS_TABLE_INFO => ['info', 'table-info'],
        self::BS_TABLE_WARNING => ['warning', 'table-warning'],
        self::BS_TABLE_DANGER => ['danger', 'table-danger'],
        self::BS_PROGRESS_BAR_ACTIVE => ['active', 'progress-bar-animated'],
        self::BS_PROGRESS_BAR_PRIMARY => ['progress-bar-primary', 'bg-primary'],
        self::BS_PROGRESS_BAR_SUCCESS => ['progress-bar-success', 'bg-success'],
        self::BS_PROGRESS_BAR_INFO => ['progress-bar-info', 'bg-info'],
        self::BS_PROGRESS_BAR_WARNING => ['progress-bar-warning', 'bg-warning'],
        self::BS_PROGRESS_BAR_DANGER => ['progress-bar-danger', 'bg-danger'],
        self::BS_WELL => ['well', ['card', 'card-body']],
        self::BS_WELL_SM => ['well-sm', ['card', 'card-body', 'p-2']],
        self::BS_WELL_LG => ['well-lg', ['card', 'card-body', 'p-4']],
        self::BS_THUMBNAIL => ['thumbnail', ['card', 'card-body']],
        self::BS_NAVBAR_DEFAULT => ['navbar-default', 'navbar-light'],
        self::BS_NAVBAR_TOGGLE => ['navbar-toggle', 'navbar-toggler'],
        self::BS_NAVBAR_RIGHT => ['navbar-right', 'ml-auto'],
        self::BS_NAVBAR_BTN => ['navbar-btn', 'nav-item'],
        self::BS_NAVBAR_FIXTOP => ['navbar-fixed-top', 'fixed-top'],
        self::BS_NAV_STACKED => ['nav-stacked', 'flex-column'],
        self::BS_NAV_ITEM => ['', 'nav-item'],
        self::BS_NAV_LINK => ['', 'nav-link'],
        self::BS_PAGE_ITEM => ['', 'page-item'],
        self::BS_PAGE_LINK => ['', 'page-link'],
        self::BS_LIST_INLINE_ITEM => ['', 'list-inline-item'],
        self::BS_BTN_DEFAULT => ['btn-default', 'btn-secondary'],
        self::BS_IMG_RESPONSIVE => ['img-responsive', 'img-fluid'],
        self::BS_IMG_CIRCLE => ['img-circle', 'rounded-circle'],
        self::BS_IMG_ROUNDED => ['img-rounded', 'rounded'],
        self::BS_RADIO => ['radio', 'form-check'],
        self::BS_CHECKBOX => ['checkbox', 'form-check'],
        self::BS_INPUT_LG => ['input-lg', 'form-control-lg'],
        self::BS_INPUT_SM => ['input-sm', 'form-control-sm'],
        self::BS_CONTROL_LABEL => ['control-label', 'col-form-label'],
        self::BS_TABLE_CONDENSED => ['table-condensed', 'table-sm'],
        self::BS_CAROUSEL_ITEM => ['item', 'carousel-item'],
        self::BS_CAROUSEL_ITEM_NEXT => ['next', 'carousel-item-next'],
        self::BS_CAROUSEL_ITEM_PREV => ['prev', 'carousel-item-prev'],
        self::BS_CAROUSEL_ITEM_LEFT => ['left', 'carousel-item-left'],
        self::BS_CAROUSEL_ITEM_RIGHT => ['right', 'carousel-item-right'],
        self::BS_CAROUSEL_CONTROL_LEFT => [['carousel-control', 'left'], 'carousel-control-left'],
        self::BS_CAROUSEL_CONTROL_RIGHT => [['carousel-control', 'right'], 'carousel-control-right'],
        self::BS_HELP_BLOCK => ['help-block', 'form-text'],
        self::BS_PULL_RIGHT => ['pull-right', 'float-right'],
        self::BS_PULL_LEFT => ['pull-left', 'float-left'],
        self::BS_CENTER_BLOCK => ['center-block', ['mx-auto', 'd-block']],
        self::BS_HIDE => ['hide', 'd-none'],
        self::BS_HIDDEN_PRINT => ['hidden-print', 'd-print-none'],
        self::BS_HIDDEN_XS => ['hidden-xs', ['d-none', 'd-sm-block']],
        self::BS_HIDDEN_SM => ['hidden-sm', ['d-sm-none', 'd-md-block']],
        self::BS_HIDDEN_MD => ['hidden-md', ['d-md-none', 'd-lg-block']],
        self::BS_HIDDEN_LG => ['hidden-lg', ['d-lg-none', 'd-xl-block']],
        self::BS_VISIBLE_PRINT => ['visible-print-block', ['d-print-block', 'd-none']],
        self::BS_VISIBLE_XS => ['visible-xs', ['d-block', 'd-sm-none']],
        self::BS_VISIBLE_SM => ['visible-sm', ['d-none', 'd-sm-block', 'd-md-none']],
        self::BS_VISIBLE_MD => ['visible-md', ['d-none', 'd-md-block', 'd-lg-none']],
        self::BS_VISIBLE_LG => ['visible-md', ['d-none', 'd-lg-block', 'd-xl-none']],
        self::BS_FORM_CONTROL_STATIC => ['form-control-static', 'form-control-plaintext'],
        self::BS_DROPDOWN_DIVIDER => ['divider', 'dropdown-divider'],
        self::BS_SHOW => ['in', 'show'],
    ];

    /**
     * @var int|string the bootstrap library version.
     *
     * To use with bootstrap 3 - you can set this to any string starting with 3 (e.g. `3` or `3.3.7` or `3.x`)
     * To use with bootstrap 4 - you can set this to any string starting with 4 (e.g. `4` or `4.1.1` or `4.x`)
     *
     * This property can be set up globally in Yii application params in your Yii2 application config file.
     *
     * For example:
     * `Yii::$app->params['bsVersion'] = '4.x'` to use with Bootstrap 4.x globally
     *
     * If this property is set, this setting will override the `Yii::$app->params['bsVersion']`. If this is not set, and
     * `Yii::$app->params['bsVersion']` is also not set, this will default to `3.x` (Bootstrap 3.x version).
     */
    public $bsVersion;

    /**
     * @var array the bootstrap grid column css prefixes mapping, the key is the bootstrap versions, and the value is
     * an array containing the sizes and their corresponding grid column css prefixes. The class using this trait, must
     * implement BootstrapInterface. If not set will default to:
     * ```php
     * [
     *   '3' => [
     *      self::SIZE_X_SMALL => 'col-xs-',
     *      self::SIZE_SMALL => 'col-sm-',
     *      self::SIZE_MEDIUM => 'col-md-',
     *      self::SIZE_LARGE => 'col-lg-',
     *      self::SIZE_X_LARGE => 'col-lg-',
     *   ],
     *   '4' => [
     *      self::SIZE_X_SMALL => 'col-',
     *      self::SIZE_SMALL => 'col-sm-',
     *      self::SIZE_MEDIUM => 'col-md-',
     *      self::SIZE_LARGE => 'col-lg-',
     *      self::SIZE_X_LARGE => 'col-xl-',
     *   ],
     * ];
     * ```
     */
    public $bsColCssPrefixes = [];

    /**
     * @var string default icon CSS prefix
     */
    protected $_defaultIconPrefix;

    /**
     * @var string default bootstrap button CSS
     */
    protected $_defaultBtnCss;

    /**
     * @var bool flag to detect whether bootstrap 4.x version is set
     */
    protected $_isBs4;

    /**
     * Initializes bootstrap versions for the widgets and asset bundles.
     * Sets the [[_isBs4]] flag by parsing [[bsVersion]]
     *
     * @throws InvalidConfigException
     */
    protected function initBsVersion()
    {
        $ver = $this->configureBsVersion();
        $this->_defaultIconPrefix = 'glyphicon glyphicon-';
        $this->_defaultBtnCss = 'btn-default';
        $ext = 'bootstrap' . ($this->_isBs4 ? '4' : '');
        if (!class_exists("yii\\{$ext}\\Html")) {
            $message = "You must install 'yiisoft/yii2-{$ext}' extension for Bootstrap {$ver}.x version support. " .
                "Dependency to 'yii2-{$ext}' has not been included with 'yii2-krajee-base'. To resolve, you must add " .
                "'yiisoft/yii2-{$ext}' to the 'require' section of your application's composer.json file and then " .
                "run 'composer update'.\n\n" .
                "NOTE: This dependency change has been done since v2.0 of 'yii2-krajee-base' because only one of " .
                "'yiisoft/yii2-bootstrap' OR 'yiisoft/bootstrap4' bootstrap extensions can be installed. The " .
                "developer can thus choose and control which bootstrap extension library to install.";
            throw new InvalidConfigException($message);
        }
        if ($this->_isBs4) {
            $this->_defaultIconPrefix = 'fas fa-';
            $this->_defaultBtnCss = 'btn-outline-secondary';
        }
        $interface = BootstrapInterface::class;
        if (!($this instanceof $interface)) {
            $class = get_called_class();
            throw new InvalidConfigException("'{$class}' must implement '{$interface}'.");
        }
        if (empty($this->bsColCssPrefixes)) {
            $this->bsColCssPrefixes = [
                '3' => [
                    self::SIZE_X_SMALL => 'col-xs-',
                    self::SIZE_SMALL => 'col-sm-',
                    self::SIZE_MEDIUM => 'col-md-',
                    self::SIZE_LARGE => 'col-lg-',
                    self::SIZE_X_LARGE => 'col-lg-',
                ],
                '4' => [
                    self::SIZE_X_SMALL => 'col-',
                    self::SIZE_SMALL => 'col-sm-',
                    self::SIZE_MEDIUM => 'col-md-',
                    self::SIZE_LARGE => 'col-lg-',
                    self::SIZE_X_LARGE => 'col-xl-',
                ],
            ];
        }
    }

    /**
     * Configures the bootstrap version settings
     * @return string the bootstrap lib parsed version number
     */
    protected function configureBsVersion()
    {
        $v = empty($this->bsVersion) ? ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3') : $this->bsVersion;
        $ver = static::parseVer($v);
        $this->_isBs4 = $ver === '4';
        return $ver;
    }

    /**
     * Validate if Bootstrap 4.x version
     * @return bool
     *
     * @throws InvalidConfigException
     */
    public function isBs4()
    {
        if (!isset($this->_isBs4)) {
            $this->configureBsVersion();
        }
        return $this->_isBs4;
    }

    /**
     * Gets the default button CSS
     * @return string
     */
    public function getDefaultBtnCss()
    {
        return $this->_defaultBtnCss;
    }

    /**
     * Gets the default icon css prefix
     * @return string
     */
    public function getDefaultIconPrefix()
    {
        return $this->_defaultIconPrefix;
    }

    /**
     * Gets bootstrap css class by parsing the bootstrap version for the specified BS CSS type
     * @param string $type the bootstrap CSS class type
     * @param bool $asString whether to return classes as a string separated by space
     * @return string
     * @throws InvalidConfigException
     */
    public function getCssClass($type, $asString = true)
    {
        if (empty(static::$bsCssMap[$type])) {
            return '';
        }
        $config = static::$bsCssMap[$type];
        $i = $this->isBs4() ? 1 : 0;
        $css = !empty($config[$i]) ? $config[$i] : '';
        return $asString && is_array($css) ? implode(' ', $css) : $css;
    }

    /**
     * Adds bootstrap CSS class to options by parsing the bootstrap version for the specified Bootstrap CSS type
     * @param array $options the HTML attributes for the container element that will be modified
     * @param string $type the bootstrap CSS class type
     * @return \kartik\base\Widget|mixed current object instance that uses this trait
     * @throws InvalidConfigException
     */
    public function addCssClass(&$options, $type)
    {
        $css = $this->getCssClass($type, false);
        if (!empty($css)) {
            Html::addCssClass($options, $css);
        }
        return $this;
    }

    /**
     * Removes bootstrap CSS class from options by parsing the bootstrap version for the specified Bootstrap CSS type
     * @param array $options the HTML attributes for the container element that will be modified
     * @param string $type the bootstrap CSS class type
     * @return \kartik\base\Widget|mixed current object instance that uses this trait
     * @throws InvalidConfigException
     */
    public function removeCssClass(&$options, $type)
    {
        $css = $this->getCssClass($type, false);
        if (!empty($css)) {
            Html::removeCssClass($options, $css);
        }
        return $this;
    }

    /**
     * Parses and returns the major BS version
     * @param string $ver
     * @return bool|string
     */
    protected static function parseVer($ver)
    {
        $ver = (string)$ver;
        return substr(trim($ver), 0, 1);
    }

    /**
     * Compares two versions and checks if they are of the same major BS version
     * @param int|string $ver1 first version
     * @param int|string $ver2 second version
     * @return bool whether major versions are equal
     */
    protected static function isSameVersion($ver1, $ver2)
    {
        if ($ver1 === $ver2 || (empty($ver1) && empty($ver2))) {
            return true;
        }
        return static::parseVer($ver1) === static::parseVer($ver2);
    }
}