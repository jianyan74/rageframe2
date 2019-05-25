<?php

namespace xj\oauth;

use xj\oauth\exception\WeixinException;
use xj\oauth\weixin\models\MpUserInfoResult;
use Yii;
use xj\oauth\weixin\models\MpTicketResult;
use xj\oauth\weixin\models\MpAccessTokenResult;
use xj\oauth\exception\WeixinAccessTokenException;
use xj\oauth\exception\WeixinTicketException;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;
use yii\base\Exception;
use yii\web\HttpException;

/**
 * Weixin 开放平台
 * @author xjflyttp <xjflyttp@gmail.com>
 * @see http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
 */
class WeixinMpAuth extends OAuth2 implements IAuth
{

    public $authUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    public $apiBaseUrl = 'https://api.weixin.qq.com';
    public $scope = 'snsapi_base';

    /**
     * Composes user authorization URL.
     * @param array $params additional auth GET params.
     * @return string authorization URL.
     */
    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'appid' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }

        if ($this->validateAuthState) {
            $authState = $this->generateAuthState();
            $this->setState('authState', $authState);
            $defaultParams['state'] = $authState;
        }

        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }

    /**
     * Fetches access token from authorization code.
     * @param string $authCode authorization code, usually comes at $_GET['code'].
     * @param array $params additional request params.
     * @return OAuthToken access token.
     * @throws HttpException on invalid auth state in case [[enableStateValidation]] is enabled.
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        if ($this->validateAuthState) {
            $authState = $this->getState('authState');
            if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
                throw new HttpException(400, 'Invalid auth state parameter.');
            } else {
                $this->removeState('authState');
            }
        }

        $defaultParams = [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'code' => $authCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getReturnUrl(),
        ];

        $request = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($this->tokenUrl)
            ->setData(array_merge($defaultParams, $params));

        $response = $this->sendRequest($request);

        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);

        return $token;
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
        $request = $event->request;
        $data = $request->getData();
//        $data['openid'] = $this->getOpenid();
        $request->setData($data);

        parent::beforeApiRequestSend($event);
    }

    /**
     *
     * @return []
     */
    protected function initUserAttributes()
    {
        $tokenParams = $this->getAccessToken()->params;
        return [
            'openid' => isset($tokenParams['openid']) ? $tokenParams['openid'] : '',
            'unionid' => isset($tokenParams['unionid']) ? $tokenParams['unionid'] : '',
        ];
    }

    /**
     * You must have grant scope=snsapi_userinfo
     * @return []
     * @see https://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
     */
    public function getUserInfo()
    {
        return $this->api('sns/userinfo', 'GET', [
            'openid' => $this->getOpenid()
        ]);
    }

    /**
     * @return string
     */
    public function getOpenid()
    {
        $attributes = $this->getUserAttributes();
        return $attributes['openid'];
    }

    protected function defaultName()
    {
        return 'weixin-mp';
    }

    protected function defaultTitle()
    {
        return 'WeixinMp';
    }

    /**
     * 获取公众号AccessToken
     * @return MpAccessTokenResult
     * @throws WeixinAccessTokenException
     */
    public function getMpAccessToken()
    {
        try {
            $result = $this->apiWithoutAccessToken($this->apiBaseUrl . '/cgi-bin/token', 'GET', [
                'grant_type' => 'client_credential',
                'appid' => $this->clientId,
                'secret' => $this->clientSecret,
            ]);
            return new MpAccessTokenResult($result);
        } catch (Exception $e) {
            throw new WeixinAccessTokenException($e->getMessage(), $e->getCode());
        }

    }

    /**
     * 获取jsapi|wx_card Ticket
     * @param string $accessToken
     * @param string $type jsapi|wx_card
     * @return MpTicketResult
     * @throws WeixinTicketException
     */
    public function getTicket($accessToken, $type = 'jsapi')
    {
        try {
            $result = $this->apiWithoutAccessToken($this->apiBaseUrl . '/cgi-bin/ticket/getticket', 'GET', [
                'type' => $type,
                'access_token' => $accessToken,
            ]);
            return new MpTicketResult($result);
        } catch (Exception $e) {
            throw new WeixinTicketException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $openid
     * @param string $lang
     * @return MpUserInfoResult
     * @throws Exception
     * @see http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html
     */
    public function getUserInfoByOpenid($openid, $lang = 'zh_CN')
    {
        try {
            $result = $this->api('cgi-bin/user/info', 'GET', [
                'openid' => $openid,
                'lang' => $lang,
            ]);
            return new MpUserInfoResult($result);
        } catch (Exception $e) {
            throw new WeixinException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        if (false === isset($data['access_token'])) {
            $data['access_token'] = $accessToken->getToken();
        }
        $request->setData($data);
    }

    /**
     * Performs request to the OAuth API returning response data.
     * You may use [[createRequest()]] method instead, gaining more control over request execution.
     * @see createApiRequest()
     * @param string $apiSubUrl API sub URL, which will be append to [[apiBaseUrl]], or absolute API URL.
     * @param string $method request method.
     * @param array|string $data request data or content.
     * @param array $headers additional request headers.
     * @return array API response data.
     */
    public function apiWithoutAccessToken($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        $request = $this->createRequest()
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
}

