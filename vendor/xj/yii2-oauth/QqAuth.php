<?php

namespace xj\oauth;

use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * QQ OAuth
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class QqAuth extends OAuth2 implements IAuth
{

    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    public $apiBaseUrl = 'https://graph.qq.com';

    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'get_user_info',
            ]);
        }
    }

    protected function initUserAttributes()
    {
        return $this->api('oauth2.0/me', 'GET');
    }

    /**
     *
     * @return []
     * @see http://wiki.connect.qq.com/get_user_info
     */
    public function getUserInfo()
    {
        return $this->api("user/get_user_info", 'GET', [
            'oauth_consumer_key' => $this->clientId,
            'openid' => $this->getOpenid(),
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
        return 'QQ';
    }

    protected function defaultTitle()
    {
        return 'QQ';
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

        $this->processResult($response);

        return $response->getData();
    }

    /**
     * @param Response $response
     */
    protected function processResult(Response $response)
    {
        $content = $response->getContent();
        if (strpos($content, "callback") !== 0) {
            return;
        }
        $lpos = strpos($content, "(");
        $rpos = strrpos($content, ")");
        $content = substr($content, $lpos + 1, $rpos - $lpos - 1);
        $content = trim($content);
        $response->setContent($content);
    }

}
