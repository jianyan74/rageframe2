<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\authclient\ClientInterface;

/**
 * AuthChoice prints buttons for authentication via various auth clients.
 * It opens a popup window for the client authentication process.
 * By default this widget relies on presence of [[\yii\authclient\Collection]] among application components
 * to get auth clients information.
 *
 * Example:
 *
 * ```php
 * <?= yii\authclient\widgets\AuthChoice::widget([
 *     'baseAuthUrl' => ['site/auth']
 * ]); ?>
 * ```
 *
 * You can customize the widget appearance by using [[begin()]] and [[end()]] syntax
 * along with using method [[clientLink()]] or [[createClientUrl()]].
 * For example:
 *
 * ```php
 * <?php
 * use yii\authclient\widgets\AuthChoice;
 * ?>
 * <?php $authAuthChoice = AuthChoice::begin([
 *     'baseAuthUrl' => ['site/auth']
 * ]); ?>
 * <ul>
 * <?php foreach ($authAuthChoice->getClients() as $client): ?>
 *     <li><?= $authAuthChoice->clientLink($client) ?></li>
 * <?php endforeach; ?>
 * </ul>
 * <?php AuthChoice::end(); ?>
 * ```
 *
 * This widget supports following keys for [[ClientInterface::getViewOptions()]] result:
 *
 *  - popupWidth: int, width of the popup window in pixels.
 *  - popupHeight: int, height of the popup window in pixels.
 *  - widget: array, configuration for the widget, which should be used to render a client link;
 *    such widget should be a subclass of [[AuthChoiceItem]].
 *
 * @see \yii\authclient\AuthAction
 *
 * @property array $baseAuthUrl Base auth URL configuration. This property is read-only.
 * @property ClientInterface[] $clients Auth providers. This property is read-only.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class AuthChoice extends Widget
{
    /**
     * @var string name of the auth client collection application component.
     * This component will be used to fetch services value if it is not set.
     */
    public $clientCollection = 'authClientCollection';
    /**
     * @var string name of the GET param , which should be used to passed auth client id to URL
     * defined by [[baseAuthUrl]].
     */
    public $clientIdGetParamName = 'authclient';
    /**
     * @var array the HTML attributes that should be rendered in the div HTML tag representing the container element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array additional options to be passed to the underlying JS plugin.
     */
    public $clientOptions = [];
    /**
     * @var bool indicates if popup window should be used instead of direct links.
     */
    public $popupMode = true;
    /**
     * @var bool indicates if widget content, should be rendered automatically.
     * Note: this value automatically set to 'false' at the first call of [[createClientUrl()]]
     */
    public $autoRender = true;

    /**
     * @var array configuration for the external clients base authentication URL.
     */
    private $_baseAuthUrl;
    /**
     * @var ClientInterface[] auth providers list.
     */
    private $_clients;


    /**
     * @param ClientInterface[] $clients auth providers
     */
    public function setClients(array $clients)
    {
        $this->_clients = $clients;
    }

    /**
     * @return ClientInterface[] auth providers
     */
    public function getClients()
    {
        if ($this->_clients === null) {
            $this->_clients = $this->defaultClients();
        }

        return $this->_clients;
    }

    /**
     * @param array $baseAuthUrl base auth URL configuration.
     */
    public function setBaseAuthUrl(array $baseAuthUrl)
    {
        $this->_baseAuthUrl = $baseAuthUrl;
    }

    /**
     * @return array base auth URL configuration.
     */
    public function getBaseAuthUrl()
    {
        if (!is_array($this->_baseAuthUrl)) {
            $this->_baseAuthUrl = $this->defaultBaseAuthUrl();
        }

        return $this->_baseAuthUrl;
    }

    /**
     * Returns default auth clients list.
     * @return ClientInterface[] auth clients list.
     */
    protected function defaultClients()
    {
        /* @var $collection \yii\authclient\Collection */
        $collection = Yii::$app->get($this->clientCollection);

        return $collection->getClients();
    }

    /**
     * Composes default base auth URL configuration.
     * @return array base auth URL configuration.
     */
    protected function defaultBaseAuthUrl()
    {
        $baseAuthUrl = [
            Yii::$app->controller->getRoute()
        ];
        $params = Yii::$app->getRequest()->getQueryParams();
        unset($params[$this->clientIdGetParamName]);
        $baseAuthUrl = array_merge($baseAuthUrl, $params);

        return $baseAuthUrl;
    }

    /**
     * Outputs client auth link.
     * @param ClientInterface $client external auth client instance.
     * @param string $text link text, if not set - default value will be generated.
     * @param array $htmlOptions link HTML options.
     * @return string generated HTML.
     * @throws InvalidConfigException on wrong configuration.
     */
    public function clientLink($client, $text = null, array $htmlOptions = [])
    {
        $viewOptions = $client->getViewOptions();

        if (empty($viewOptions['widget'])) {
            if ($text === null) {
                $text = Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]);
            }
            if (!isset($htmlOptions['class'])) {
                $htmlOptions['class'] = $client->getName();
            }
            if (!isset($htmlOptions['title'])) {
                $htmlOptions['title'] = $client->getTitle();
            }
            Html::addCssClass($htmlOptions, ['widget' => 'auth-link']);

            if ($this->popupMode) {
                if (isset($viewOptions['popupWidth'])) {
                    $htmlOptions['data-popup-width'] = $viewOptions['popupWidth'];
                }
                if (isset($viewOptions['popupHeight'])) {
                    $htmlOptions['data-popup-height'] = $viewOptions['popupHeight'];
                }
            }
            return Html::a($text, $this->createClientUrl($client), $htmlOptions);
        }

        $widgetConfig = $viewOptions['widget'];
        if (!isset($widgetConfig['class'])) {
            throw new InvalidConfigException('Widget config "class" parameter is missing');
        }
        /* @var $widgetClass Widget */
        $widgetClass = $widgetConfig['class'];
        if (!(is_subclass_of($widgetClass, AuthChoiceItem::className()))) {
            throw new InvalidConfigException('Item widget class must be subclass of "' . AuthChoiceItem::className() . '"');
        }
        unset($widgetConfig['class']);
        $widgetConfig['client'] = $client;
        $widgetConfig['authChoice'] = $this;
        return $widgetClass::widget($widgetConfig);
    }

    /**
     * Composes client auth URL.
     * @param ClientInterface $client external auth client instance.
     * @return string auth URL.
     */
    public function createClientUrl($client)
    {
        $this->autoRender = false;
        $url = $this->getBaseAuthUrl();
        $url[$this->clientIdGetParamName] = $client->getId();

        return Url::to($url);
    }

    /**
     * Renders the main content, which includes all external services links.
     * @return string generated HTML.
     */
    protected function renderMainContent()
    {
        $items = [];
        foreach ($this->getClients() as $externalService) {
            $items[] = Html::tag('li', $this->clientLink($externalService));
        }
        return Html::tag('ul', implode('', $items), ['class' => 'auth-clients']);
    }

    /**
     * Initializes the widget.
     */
    public function init()
    {
        $view = Yii::$app->getView();
        if ($this->popupMode) {
            AuthChoiceAsset::register($view);
            if (empty($this->clientOptions)) {
                $options = '';
            } else {
                $options = Json::htmlEncode($this->clientOptions);
            }
            $view->registerJs("jQuery('#" . $this->getId() . "').authchoice({$options});");
        } else {
            AuthChoiceStyleAsset::register($view);
        }
        $this->options['id'] = $this->getId();
        echo Html::beginTag('div', $this->options);
    }

    /**
     * Runs the widget.
     * @return string rendered HTML.
     */
    public function run()
    {
        $content = '';
        if ($this->autoRender) {
            $content .= $this->renderMainContent();
        }
        $content .= Html::endTag('div');
        return $content;
    }
}