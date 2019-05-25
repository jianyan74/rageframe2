<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient;

use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\User;

/**
 * AuthAction performs authentication via different auth clients.
 * It supports [[OpenId]], [[OAuth1]] and [[OAuth2]] client types.
 *
 * Usage:
 *
 * ```php
 * class SiteController extends Controller
 * {
 *     public function actions()
 *     {
 *         return [
 *             'auth' => [
 *                 'class' => 'yii\authclient\AuthAction',
 *                 'successCallback' => [$this, 'successCallback'],
 *             ],
 *         ]
 *     }
 *
 *     public function successCallback($client)
 *     {
 *         $attributes = $client->getUserAttributes();
 *         // user login or signup comes here
 *     }
 * }
 * ```
 *
 * Usually authentication via external services is performed inside the popup window.
 * This action handles the redirection and closing of popup window correctly.
 *
 * @see Collection
 * @see \yii\authclient\widgets\AuthChoice
 *
 * @property string $cancelUrl Cancel URL.
 * @property string $successUrl Successful URL.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class AuthAction extends Action
{
    /**
     * @var string name of the auth client collection application component.
     * It should point to [[Collection]] instance.
     */
    public $clientCollection = 'authClientCollection';
    /**
     * @var string name of the GET param, which is used to passed auth client id to this action.
     * Note: watch for the naming, make sure you do not choose name used in some auth protocol.
     */
    public $clientIdGetParamName = 'authclient';
    /**
     * @var callable PHP callback, which should be triggered in case of successful authentication.
     * This callback should accept [[ClientInterface]] instance as an argument.
     * For example:
     *
     * ```php
     * public function onAuthSuccess(ClientInterface $client)
     * {
     *     $attributes = $client->getUserAttributes();
     *     // user login or signup comes here
     * }
     * ```
     *
     * If this callback returns [[Response]] instance, it will be used as action response,
     * otherwise redirection to [[successUrl]] will be performed.
     */
    public $successCallback;
    /**
     * @var callable PHP callback, which should be triggered in case of authentication cancelation.
     * This callback should accept [[ClientInterface]] instance as an argument.
     * For example:
     *
     * ```php
     * public function onAuthCancel(ClientInterface $client)
     * {
     *     // set flash, logging, etc.
     * }
     * ```
     *
     * If this callback returns [[Response]] instance, it will be used as action response,
     * otherwise redirection to [[cancelUrl]] will be performed.
     *
     * @since 2.1.5
     */
    public $cancelCallback;
    /**
     * @var string name or alias of the view file, which should be rendered in order to perform redirection.
     * If not set - default one will be used.
     */
    public $redirectView;
    /**
     * @var User|array|string the User object or the application component ID of the user component.
     * @since 2.1.8
     */
    public $user = 'user';

    /**
     * @var string the redirect url after successful authorization.
     */
    private $_successUrl;
    /**
     * @var string the redirect url after unsuccessful authorization (e.g. user canceled).
     */
    private $_cancelUrl;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->user = Instance::ensure($this->user, User::className());
    }

    /**
     * @param string $url successful URL.
     */
    public function setSuccessUrl($url)
    {
        $this->_successUrl = $url;
    }

    /**
     * @return string successful URL.
     */
    public function getSuccessUrl()
    {
        if (empty($this->_successUrl)) {
            $this->_successUrl = $this->defaultSuccessUrl();
        }

        return $this->_successUrl;
    }

    /**
     * @param string $url cancel URL.
     */
    public function setCancelUrl($url)
    {
        $this->_cancelUrl = $url;
    }

    /**
     * @return string cancel URL.
     */
    public function getCancelUrl()
    {
        if (empty($this->_cancelUrl)) {
            $this->_cancelUrl = $this->defaultCancelUrl();
        }

        return $this->_cancelUrl;
    }

    /**
     * Creates default [[successUrl]] value.
     * @return string success URL value.
     */
    protected function defaultSuccessUrl()
    {
        return $this->user->getReturnUrl();
    }

    /**
     * Creates default [[cancelUrl]] value.
     * @return string cancel URL value.
     */
    protected function defaultCancelUrl()
    {
        return Url::to($this->user->loginUrl);
    }

    /**
     * Runs the action.
     */
    public function run()
    {
        $clientId = Yii::$app->getRequest()->getQueryParam($this->clientIdGetParamName);
        if (!empty($clientId)) {
            /* @var $collection \yii\authclient\Collection */
            $collection = Yii::$app->get($this->clientCollection);
            if (!$collection->hasClient($clientId)) {
                throw new NotFoundHttpException("Unknown auth client '{$clientId}'");
            }
            $client = $collection->getClient($clientId);

            return $this->auth($client);
        }

        throw new NotFoundHttpException();
    }

    /**
     * Perform authentication for the given client.
     * @param mixed $client auth client instance.
     * @param array $authUrlParams additional auth GET params.
     * @return Response response instance.
     * @throws \yii\base\NotSupportedException on invalid client.
     */
    protected function auth($client, $authUrlParams = [])
    {
        if ($client instanceof OAuth2) {
            return $this->authOAuth2($client, $authUrlParams);
        } elseif ($client instanceof OAuth1) {
            return $this->authOAuth1($client, $authUrlParams);
        } elseif ($client instanceof OpenId) {
            return $this->authOpenId($client);
        }

        throw new NotSupportedException('Provider "' . get_class($client) . '" is not supported.');
    }

    /**
     * This method is invoked in case of successful authentication via auth client.
     * @param ClientInterface $client auth client instance.
     * @throws InvalidConfigException on invalid success callback.
     * @return Response response instance.
     */
    protected function authSuccess($client)
    {
        if (!is_callable($this->successCallback)) {
            throw new InvalidConfigException('"' . get_class($this) . '::$successCallback" should be a valid callback.');
        }

        $response = call_user_func($this->successCallback, $client);
        if ($response instanceof Response) {
            return $response;
        }

        return $this->redirectSuccess();
    }

    /**
     * This method is invoked in case of authentication cancelation.
     * @param ClientInterface $client auth client instance.
     * @return Response response instance.
     * @since 2.1.5
     */
    protected function authCancel($client)
    {
        if ($this->cancelCallback !== null) {
            $response = call_user_func($this->cancelCallback, $client);
            if ($response instanceof Response) {
                return $response;
            }
        }

        return $this->redirectCancel();
    }

    /**
     * Redirect to the given URL or simply close the popup window.
     * @param mixed $url URL to redirect, could be a string or array config to generate a valid URL.
     * @param bool $enforceRedirect indicates if redirect should be performed even in case of popup window.
     * @return \yii\web\Response response instance.
     */
    public function redirect($url, $enforceRedirect = true)
    {
        $viewFile = $this->redirectView;
        if ($viewFile === null) {
            $viewFile = __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'redirect.php';
        } else {
            $viewFile = Yii::getAlias($viewFile);
        }

        $viewData = [
            'url' => $url,
            'enforceRedirect' => $enforceRedirect,
        ];

        $response = Yii::$app->getResponse();
        $response->content = Yii::$app->getView()->renderFile($viewFile, $viewData);

        return $response;
    }

    /**
     * Redirect to the URL. If URL is null, [[successUrl]] will be used.
     * @param string $url URL to redirect.
     * @return \yii\web\Response response instance.
     */
    public function redirectSuccess($url = null)
    {
        if ($url === null) {
            $url = $this->getSuccessUrl();
        }
        return $this->redirect($url);
    }

    /**
     * Redirect to the [[cancelUrl]] or simply close the popup window.
     * @param string $url URL to redirect.
     * @return \yii\web\Response response instance.
     */
    public function redirectCancel($url = null)
    {
        if ($url === null) {
            $url = $this->getCancelUrl();
        }
        return $this->redirect($url, false);
    }

    /**
     * Performs OpenID auth flow.
     * @param OpenId $client auth client instance.
     * @return Response action response.
     * @throws Exception on failure.
     * @throws HttpException on failure.
     */
    protected function authOpenId($client)
    {
        $request = Yii::$app->getRequest();
        $mode = $request->get('openid_mode', $request->post('openid_mode'));

        if (empty($mode)) {
            $url = $client->buildAuthUrl();
            return Yii::$app->getResponse()->redirect($url);
        }

        switch ($mode) {
            case 'id_res':
                if ($client->validate()) {
                    return $this->authSuccess($client);
                }
                throw new HttpException(400, 'Unable to complete the authentication because the required data was not received.');
            case 'cancel':
                return $this->authCancel($client);
            default:
                throw new HttpException(400);
        }
    }

    /**
     * Performs OAuth1 auth flow.
     * @param OAuth1 $client auth client instance.
     * @param array $authUrlParams additional auth GET params.
     * @return Response action response.
     */
    protected function authOAuth1($client, $authUrlParams = [])
    {
        $request = Yii::$app->getRequest();

        // user denied error
        if ($request->get('denied') !== null) {
            return $this->authCancel($client);
        }

        if (($oauthToken = $request->get('oauth_token', $request->post('oauth_token'))) !== null) {
            // Upgrade to access token.
            $client->fetchAccessToken($oauthToken);
            return $this->authSuccess($client);
        }

        // Get request token.
        $requestToken = $client->fetchRequestToken();
        // Get authorization URL.
        $url = $client->buildAuthUrl($requestToken, $authUrlParams);
        // Redirect to authorization URL.
        return Yii::$app->getResponse()->redirect($url);
    }

    /**
     * Performs OAuth2 auth flow.
     * @param OAuth2 $client auth client instance.
     * @param array $authUrlParams additional auth GET params.
     * @return Response action response.
     * @throws \yii\base\Exception on failure.
     */
    protected function authOAuth2($client, $authUrlParams = [])
    {
        $request = Yii::$app->getRequest();

        if (($error = $request->get('error')) !== null) {
            if (
                $error === 'access_denied' ||
                $error === 'user_cancelled_login' ||
                $error === 'user_cancelled_authorize'
            ) {
                // user denied error
                return $this->authCancel($client);
            }
            // request error
            $errorMessage = $request->get('error_description', $request->get('error_message'));
            if ($errorMessage === null) {
                $errorMessage = http_build_query($request->get());
            }
            throw new Exception('Auth error: ' . $errorMessage);
        }

        // Get the access_token and save them to the session.
        if (($code = $request->get('code')) !== null) {
            $token = $client->fetchAccessToken($code);
            if (!empty($token)) {
                return $this->authSuccess($client);
            }
            return $this->authCancel($client);
        }

        $url = $client->buildAuthUrl($authUrlParams);
        return Yii::$app->getResponse()->redirect($url);
    }
}
