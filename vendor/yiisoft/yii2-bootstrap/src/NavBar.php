<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\bootstrap;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * NavBar renders a navbar HTML component.
 *
 * Any content enclosed between the [[begin()]] and [[end()]] calls of NavBar
 * is treated as the content of the navbar. You may use widgets such as [[Nav]]
 * or [[\yii\widgets\Menu]] to build up such content. For example,
 *
 * ```php
 * use yii\bootstrap\NavBar;
 * use yii\bootstrap\Nav;
 *
 * NavBar::begin(['brandLabel' => 'NavBar Test']);
 * echo Nav::widget([
 *     'items' => [
 *         ['label' => 'Home', 'url' => ['/site/index']],
 *         ['label' => 'About', 'url' => ['/site/about']],
 *     ],
 *     'options' => ['class' => 'navbar-nav'],
 * ]);
 * NavBar::end();
 * ```
 *
 * @see https://getbootstrap.com/docs/3.3/components/#navbar
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class NavBar extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "nav", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array the HTML attributes for the container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "div", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $containerOptions = [];
    /**
     * @var string|bool the text of the brand or false if it's not used. Note that this is not HTML-encoded.
     * @see https://getbootstrap.com/docs/3.3/components/#navbar
     */
    public $brandLabel = false;
    /**
     * @var string|bool src of the brand image or false if it's not used. Note that this param will override `$this->brandLabel` param.
     * @see https://getbootstrap.com/docs/3.3/components/#navbar
     * @since 2.0.8
     */
    public $brandImage = false;
    /**
     * @var array|string|bool $url the URL for the brand's hyperlink tag. This parameter will be processed by [[\yii\helpers\Url::to()]]
     * and will be used for the "href" attribute of the brand link. Default value is false that means
     * [[\yii\web\Application::homeUrl]] will be used.
     * You may set it to `null` if you want to have no link at all.
     */
    public $brandUrl = false;
    /**
     * @var array the HTML attributes of the brand link.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $brandOptions = [];
    /**
     * @var string HTML content to be added in navbar-header div, for example, mobile search form.
     * @since 2.0.8
     */
    public $headerContent;
    /**
     * @var string text to show for screen readers for the button to toggle the navbar.
     */
    public $screenReaderToggleText = 'Toggle navigation';
    /**
     * @var bool whether the navbar content should be included in an inner div container which by default
     * adds left and right padding. Set this to false for a 100% width navbar.
     */
    public $renderInnerContainer = true;
    /**
     * @var array the HTML attributes of the inner container.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $innerContainerOptions = [];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->clientOptions = false;
        if (empty($this->options['class'])) {
            Html::addCssClass($this->options, ['navbar', 'navbar-default']);
        } else {
            Html::addCssClass($this->options, ['widget' => 'navbar']);
        }
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'nav');
        echo Html::beginTag($tag, $options);
        if ($this->renderInnerContainer) {
            if (!isset($this->innerContainerOptions['class'])) {
                Html::addCssClass($this->innerContainerOptions, 'container');
            }
            echo Html::beginTag('div', $this->innerContainerOptions);
        }
        echo Html::beginTag('div', ['class' => 'navbar-header']);
        if (!isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = "{$this->options['id']}-collapse";
        }
        echo $this->renderToggleButton();
        if ($this->brandImage !== false) {
            $this->brandLabel = Html::img($this->brandImage);
        }
        if ($this->brandLabel !== false) {
            Html::addCssClass($this->brandOptions, ['widget' => 'navbar-brand']);
            echo Html::a($this->brandLabel, $this->brandUrl === false ? Yii::$app->homeUrl : $this->brandUrl, $this->brandOptions);
        }
        echo $this->headerContent;
        echo Html::endTag('div');
        Html::addCssClass($this->containerOptions, ['collapse' => 'collapse', 'widget' => 'navbar-collapse']);
        $options = $this->containerOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'div');
        echo Html::endTag($tag);
        if ($this->renderInnerContainer) {
            echo Html::endTag('div');
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'nav');
        echo Html::endTag($tag);
        BootstrapPluginAsset::register($this->getView());
    }

    /**
     * Renders collapsible toggle button.
     * @return string the rendering toggle button.
     */
    protected function renderToggleButton()
    {
        $bar = Html::tag('span', '', ['class' => 'icon-bar']);
        $screenReader = "<span class=\"sr-only\">{$this->screenReaderToggleText}</span>";

        return Html::button("{$screenReader}\n{$bar}\n{$bar}\n{$bar}", [
            'class' => 'navbar-toggle',
            'data-toggle' => 'collapse',
            'data-target' => "#{$this->containerOptions['id']}",
        ]);
    }
}
