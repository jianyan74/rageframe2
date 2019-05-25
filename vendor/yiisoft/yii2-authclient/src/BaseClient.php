<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\httpclient\Client;

/**
 * BaseClient is a base Auth Client class.
 *
 * @see ClientInterface
 *
 * @property Client $httpClient Internal HTTP client. Note that the type of this property differs in getter
 * and setter. See [[getHttpClient()]] and [[setHttpClient()]] for details.
 * @property string $id Service id.
 * @property string $name Service name.
 * @property array $normalizeUserAttributeMap Normalize user attribute map.
 * @property array $requestOptions HTTP request options. This property is read-only.
 * @property StateStorageInterface $stateStorage Stage storage. Note that the type of this property differs in
 * getter and setter. See [[getStateStorage()]] and [[setStateStorage()]] for details.
 * @property string $title Service title.
 * @property array $userAttributes List of user attributes.
 * @property array $viewOptions View options in format: optionName => optionValue.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
abstract class BaseClient extends Component implements ClientInterface
{
    /**
     * @var string auth service id.
     * This value mainly used as HTTP request parameter.
     */
    private $_id;
    /**
     * @var string auth service name.
     * This value may be used in database records, CSS files and so on.
     */
    private $_name;
    /**
     * @var string auth service title to display in views.
     */
    private $_title;
    /**
     * @var array authenticated user attributes.
     */
    private $_userAttributes;
    /**
     * @var array map used to normalize user attributes fetched from external auth service
     * in format: normalizedAttributeName => sourceSpecification
     * 'sourceSpecification' can be:
     * - string, raw attribute name
     * - array, pass to raw attribute value
     * - callable, PHP callback, which should accept array of raw attributes and return normalized value.
     *
     * For example:
     *
     * ```php
     * 'normalizeUserAttributeMap' => [
     *      'about' => 'bio',
     *      'language' => ['languages', 0, 'name'],
     *      'fullName' => function ($attributes) {
     *          return $attributes['firstName'] . ' ' . $attributes['lastName'];
     *      },
     *  ],
     * ```
     */
    private $_normalizeUserAttributeMap;
    /**
     * @var array view options in format: optionName => optionValue
     */
    private $_viewOptions;
    /**
     * @var Client|array|string internal HTTP client.
     * @since 2.1
     */
    private $_httpClient = 'yii\httpclient\Client';
    /**
     * @var array cURL request options. Option values from this field will overwrite corresponding
     * values from [[defaultRequestOptions()]].
     * @since 2.1
     */
    private $_requestOptions = [];
    /**
     * @var StateStorageInterface|array|string state storage to be used.
     */
    private $_stateStorage = 'yii\authclient\SessionStateStorage';


    /**
     * @param string $id service id.
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string service id
     */
    public function getId()
    {
        if (empty($this->_id)) {
            $this->_id = $this->getName();
        }

        return $this->_id;
    }

    /**
     * @param string $name service name.
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string service name.
     */
    public function getName()
    {
        if ($this->_name === null) {
            $this->_name = $this->defaultName();
        }

        return $this->_name;
    }

    /**
     * @param string $title service title.
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string service title.
     */
    public function getTitle()
    {
        if ($this->_title === null) {
            $this->_title = $this->defaultTitle();
        }

        return $this->_title;
    }

    /**
     * @param array $userAttributes list of user attributes
     */
    public function setUserAttributes($userAttributes)
    {
        $this->_userAttributes = $this->normalizeUserAttributes($userAttributes);
    }

    /**
     * @return array list of user attributes
     */
    public function getUserAttributes()
    {
        if ($this->_userAttributes === null) {
            $this->_userAttributes = $this->normalizeUserAttributes($this->initUserAttributes());
        }

        return $this->_userAttributes;
    }

    /**
     * @param array $normalizeUserAttributeMap normalize user attribute map.
     */
    public function setNormalizeUserAttributeMap($normalizeUserAttributeMap)
    {
        $this->_normalizeUserAttributeMap = $normalizeUserAttributeMap;
    }

    /**
     * @return array normalize user attribute map.
     */
    public function getNormalizeUserAttributeMap()
    {
        if ($this->_normalizeUserAttributeMap === null) {
            $this->_normalizeUserAttributeMap = $this->defaultNormalizeUserAttributeMap();
        }

        return $this->_normalizeUserAttributeMap;
    }

    /**
     * @param array $viewOptions view options in format: optionName => optionValue
     */
    public function setViewOptions($viewOptions)
    {
        $this->_viewOptions = $viewOptions;
    }

    /**
     * @return array view options in format: optionName => optionValue
     */
    public function getViewOptions()
    {
        if ($this->_viewOptions === null) {
            $this->_viewOptions = $this->defaultViewOptions();
        }

        return $this->_viewOptions;
    }

    /**
     * Returns HTTP client.
     * @return Client internal HTTP client.
     * @since 2.1
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = $this->createHttpClient($this->_httpClient);
        }
        return $this->_httpClient;
    }

    /**
     * Sets HTTP client to be used.
     * @param array|Client $httpClient internal HTTP client.
     * @since 2.1
     */
    public function setHttpClient($httpClient)
    {
        $this->_httpClient = $httpClient;
    }

    /**
     * @param array $options HTTP request options.
     * @since 2.1
     */
    public function setRequestOptions(array $options)
    {
        $this->_requestOptions = $options;
    }

    /**
     * @return array HTTP request options.
     * @since 2.1
     */
    public function getRequestOptions()
    {
        return $this->_requestOptions;
    }

    /**
     * @return StateStorageInterface stage storage.
     */
    public function getStateStorage()
    {
        if (!is_object($this->_stateStorage)) {
            $this->_stateStorage = Yii::createObject($this->_stateStorage);
        }
        return $this->_stateStorage;
    }

    /**
     * @param StateStorageInterface|array|string $stateStorage stage storage to be used.
     */
    public function setStateStorage($stateStorage)
    {
        $this->_stateStorage = $stateStorage;
    }

    /**
     * Generates service name.
     * @return string service name.
     */
    protected function defaultName()
    {
        return Inflector::camel2id(StringHelper::basename(get_class($this)));
    }

    /**
     * Generates service title.
     * @return string service title.
     */
    protected function defaultTitle()
    {
        return StringHelper::basename(get_class($this));
    }

    /**
     * Initializes authenticated user attributes.
     * @return array auth user attributes.
     */
    abstract protected function initUserAttributes();

    /**
     * Returns the default [[normalizeUserAttributeMap]] value.
     * Particular client may override this method in order to provide specific default map.
     * @return array normalize attribute map.
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [];
    }

    /**
     * Returns the default [[viewOptions]] value.
     * Particular client may override this method in order to provide specific default view options.
     * @return array list of default [[viewOptions]]
     */
    protected function defaultViewOptions()
    {
        return [];
    }

    /**
     * Creates HTTP client instance from reference or configuration.
     * @param string|array $reference component name or array configuration.
     * @return Client HTTP client instance.
     * @since 2.1
     */
    protected function createHttpClient($reference)
    {
        return Instance::ensure($reference, Client::className());
    }

    /**
     * Normalize given user attributes according to [[normalizeUserAttributeMap]].
     * @param array $attributes raw attributes.
     * @throws InvalidConfigException on incorrect normalize attribute map.
     * @return array normalized attributes.
     */
    protected function normalizeUserAttributes($attributes)
    {
        foreach ($this->getNormalizeUserAttributeMap() as $normalizedName => $actualName) {
            if (is_scalar($actualName)) {
                if (array_key_exists($actualName, $attributes)) {
                    $attributes[$normalizedName] = $attributes[$actualName];
                }
            } else {
                if (is_callable($actualName)) {
                    $attributes[$normalizedName] = call_user_func($actualName, $attributes);
                } elseif (is_array($actualName)) {
                    $haystack = $attributes;
                    $searchKeys = $actualName;
                    $isFound = true;
                    while (($key = array_shift($searchKeys)) !== null) {
                        if (is_array($haystack) && array_key_exists($key, $haystack)) {
                            $haystack = $haystack[$key];
                        } else {
                            $isFound = false;
                            break;
                        }
                    }
                    if ($isFound) {
                        $attributes[$normalizedName] = $haystack;
                    }
                } else {
                    throw new InvalidConfigException('Invalid actual name "' . gettype($actualName) . '" specified at "' . get_class($this) . '::normalizeUserAttributeMap"');
                }
            }
        }

        return $attributes;
    }

    /**
     * Creates HTTP request instance.
     * @return \yii\httpclient\Request HTTP request instance.
     * @since 2.1
     */
    public function createRequest()
    {
        return $this->getHttpClient()
            ->createRequest()
            ->addOptions($this->defaultRequestOptions())
            ->addOptions($this->getRequestOptions());
    }

    /**
     * Returns default HTTP request options.
     * @return array HTTP request options.
     * @since 2.1
     */
    protected function defaultRequestOptions()
    {
        return [
            'timeout' => 30,
            'sslVerifyPeer' => false,
        ];
    }

    /**
     * Sets persistent state.
     * @param string $key state key.
     * @param mixed $value state value
     * @return $this the object itself
     */
    protected function setState($key, $value)
    {
        $this->getStateStorage()->set($this->getStateKeyPrefix() . $key, $value);
        return $this;
    }

    /**
     * Returns persistent state value.
     * @param string $key state key.
     * @return mixed state value.
     */
    protected function getState($key)
    {
        return $this->getStateStorage()->get($this->getStateKeyPrefix() . $key);
    }

    /**
     * Removes persistent state value.
     * @param string $key state key.
     * @return bool success.
     */
    protected function removeState($key)
    {
        return $this->getStateStorage()->remove($this->getStateKeyPrefix() . $key);
    }

    /**
     * Returns session key prefix, which is used to store internal states.
     * @return string session key prefix.
     */
    protected function getStateKeyPrefix()
    {
        return get_class($this) . '_' . $this->getId() . '_';
    }
}
