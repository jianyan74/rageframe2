<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient;

use yii\base\Exception;
use yii\base\InvalidParamException;
use Yii;
use yii\httpclient\Request;

/**
 * BaseOAuth is a base class for the OAuth clients.
 *
 * @see http://oauth.net/
 *
 * @property OAuthToken $accessToken Auth token instance. Note that the type of this property differs in
 * getter and setter. See [[getAccessToken()]] and [[setAccessToken()]] for details.
 * @property string $returnUrl Return URL.
 * @property signature\BaseMethod $signatureMethod Signature method instance. Note that the type of this
 * property differs in getter and setter. See [[getSignatureMethod()]] and [[setSignatureMethod()]] for details.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
abstract class BaseOAuth extends BaseClient
{
    /**
     * @var string protocol version.
     */
    public $version = '1.0';
    /**
     * @var string API base URL.
     * This field will be used as [[\yii\httpclient\Client::baseUrl]] value of [[httpClient]].
     * Note: changing this property will take no effect after [[httpClient]] is instantiated.
     */
    public $apiBaseUrl;
    /**
     * @var string authorize URL.
     */
    public $authUrl;
    /**
     * @var string auth request scope.
     */
    public $scope;
    /**
     * @var bool whether to automatically perform 'refresh access token' request on expired access token.
     * @since 2.0.6
     */
    public $autoRefreshAccessToken = true;

    /**
     * @var string URL, which user will be redirected after authentication at the OAuth provider web site.
     * Note: this should be absolute URL (with http:// or https:// leading).
     * By default current URL will be used.
     */
    private $_returnUrl;
    /**
     * @var OAuthToken|array access token instance or its array configuration.
     */
    private $_accessToken;
    /**
     * @var signature\BaseMethod|array signature method instance or its array configuration.
     */
    private $_signatureMethod = [];


    /**
     * @param string $returnUrl return URL
     */
    public function setReturnUrl($returnUrl)
    {
        $this->_returnUrl = $returnUrl;
    }

    /**
     * @return string return URL.
     */
    public function getReturnUrl()
    {
        if ($this->_returnUrl === null) {
            $this->_returnUrl = $this->defaultReturnUrl();
        }
        return $this->_returnUrl;
    }

    /**
     * Sets access token to be used.
     * @param array|OAuthToken $token access token or its configuration.
     */
    public function setAccessToken($token)
    {
        if (!is_object($token) && $token !== null) {
            $token = $this->createToken($token);
        }
        $this->_accessToken = $token;
        $this->saveAccessToken($token);
    }

    /**
     * @return OAuthToken auth token instance.
     */
    public function getAccessToken()
    {
        if (!is_object($this->_accessToken)) {
            $this->_accessToken = $this->restoreAccessToken();
        }

        return $this->_accessToken;
    }

    /**
     * Set signature method to be used.
     * @param array|signature\BaseMethod $signatureMethod signature method instance or its array configuration.
     * @throws InvalidParamException on wrong argument.
     */
    public function setSignatureMethod($signatureMethod)
    {
        if (!is_object($signatureMethod) && !is_array($signatureMethod)) {
            throw new InvalidParamException('"' . get_class($this) . '::signatureMethod" should be instance of "\yii\autclient\signature\BaseMethod" or its array configuration. "' . gettype($signatureMethod) . '" has been given.');
        }
        $this->_signatureMethod = $signatureMethod;
    }

    /**
     * @return signature\BaseMethod signature method instance.
     */
    public function getSignatureMethod()
    {
        if (!is_object($this->_signatureMethod)) {
            $this->_signatureMethod = $this->createSignatureMethod($this->_signatureMethod);
        }

        return $this->_signatureMethod;
    }

    /**
     * {@inheritdoc}
     */
    public function setHttpClient($httpClient)
    {
        if (is_object($httpClient)) {
            $httpClient = clone $httpClient;
            $httpClient->baseUrl = $this->apiBaseUrl;
        }
        parent::setHttpClient($httpClient);
    }

    /**
     * {@inheritdoc}
     */
    protected function createHttpClient($reference)
    {
        $httpClient = parent::createHttpClient($reference);
        $httpClient->baseUrl = $this->apiBaseUrl;
        return $httpClient;
    }

    /**
     * Composes default [[returnUrl]] value.
     * @return string return URL.
     */
    protected function defaultReturnUrl()
    {
        return Yii::$app->getRequest()->getAbsoluteUrl();
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultRequestOptions()
    {
        return [
            'userAgent' => Yii::$app->name . ' OAuth ' . $this->version . ' Client',
            'timeout' => 30,
            'sslVerifyPeer' => false,
        ];
    }

    /**
     * Creates signature method instance from its configuration.
     * @param array $signatureMethodConfig signature method configuration.
     * @return signature\BaseMethod signature method instance.
     */
    protected function createSignatureMethod(array $signatureMethodConfig)
    {
        if (!array_key_exists('class', $signatureMethodConfig)) {
            $signatureMethodConfig['class'] = signature\HmacSha1::className();
        }
        return Yii::createObject($signatureMethodConfig);
    }

    /**
     * Creates token from its configuration.
     * @param array $tokenConfig token configuration.
     * @return OAuthToken token instance.
     */
    protected function createToken(array $tokenConfig = [])
    {
        if (!array_key_exists('class', $tokenConfig)) {
            $tokenConfig['class'] = OAuthToken::className();
        }
        return Yii::createObject($tokenConfig);
    }

    /**
     * Sends the given HTTP request, returning response data.
     * @param \yii\httpclient\Request $request HTTP request to be sent.
     * @return array response data.
     * @throws InvalidResponseException on invalid remote response.
     * @since 2.1
     */
    protected function sendRequest($request)
    {
        $response = $request->send();

        if (!$response->getIsOk()) {
            throw new InvalidResponseException($response, 'Request failed with code: ' . $response->getStatusCode() . ', message: ' . $response->getContent());
        }

        return $response->getData();
    }

    /**
     * Composes URL from base URL and GET params.
     * @param string $url base URL.
     * @param array $params GET params.
     * @return string composed URL.
     */
    protected function composeUrl($url, array $params = [])
    {
        if (!empty($params)) {
            if (strpos($url, '?') === false) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        }
        return $url;
    }

    /**
     * Saves token as persistent state.
     * @param OAuthToken|null $token auth token to be saved.
     * @return $this the object itself.
     */
    protected function saveAccessToken($token)
    {
        return $this->setState('token', $token);
    }

    /**
     * Restores access token.
     * @return OAuthToken auth token.
     */
    protected function restoreAccessToken()
    {
        $token = $this->getState('token');
        if (is_object($token)) {
            /* @var $token OAuthToken */
            if ($token->getIsExpired() && $this->autoRefreshAccessToken) {
                $token = $this->refreshAccessToken($token);
            }
        }
        return $token;
    }

    /**
     * Creates an HTTP request for the API call.
     * The created request will be automatically processed adding access token parameters and signature
     * before sending. You may use [[createRequest()]] to gain full control over request composition and execution.
     * @see createRequest()
     * @return Request HTTP request instance.
     * @since 2.1
     */
    public function createApiRequest()
    {
        $request = $this->createRequest();
        $request->on(Request::EVENT_BEFORE_SEND, [$this, 'beforeApiRequestSend']);
        return $request;
    }

    /**
     * Handles [[Request::EVENT_BEFORE_SEND]] event.
     * Applies [[accessToken]] to the request.
     * @param \yii\httpclient\RequestEvent $event event instance.
     * @throws Exception on invalid access token.
     * @since 2.1
     */
    public function beforeApiRequestSend($event)
    {
        $accessToken = $this->getAccessToken();
        if (!is_object($accessToken) || !$accessToken->getIsValid()) {
            throw new Exception('Invalid access token.');
        }

        $this->applyAccessTokenToRequest($event->request, $accessToken);
    }

    /**
     * Performs request to the OAuth API returning response data.
     * You may use [[createApiRequest()]] method instead, gaining more control over request execution.
     * @see createApiRequest()
     * @param string $apiSubUrl API sub URL, which will be append to [[apiBaseUrl]], or absolute API URL.
     * @param string $method request method.
     * @param array|string $data request data or content.
     * @param array $headers additional request headers.
     * @return array API response data.
     */
    public function api($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        $request = $this->createApiRequest()
            ->setMethod($method)
            ->setUrl($apiSubUrl)
            ->addHeaders($headers);

        if (!empty($data)) {
            if (is_array($data)) {
                $request->setData($data);
            } else {
                $request->setContent($data);
            }
        }

        return $this->sendRequest($request);
    }

    /**
     * Gets new auth token to replace expired one.
     * @param OAuthToken $token expired auth token.
     * @return OAuthToken new auth token.
     */
    abstract public function refreshAccessToken(OAuthToken $token);

    /**
     * Applies access token to the HTTP request instance.
     * @param \yii\httpclient\Request $request HTTP request instance.
     * @param OAuthToken $accessToken access token instance.
     * @since 2.1
     */
    abstract public function applyAccessTokenToRequest($request, $accessToken);
}
