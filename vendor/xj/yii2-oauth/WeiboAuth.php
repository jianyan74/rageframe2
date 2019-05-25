<?php

namespace xj\oauth;

use yii\authclient\OAuth2;

/**
 * Sina Weibo OAuth
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class WeiboAuth extends OAuth2 implements IAuth
{

    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    public $apiBaseUrl = 'https://api.weibo.com';

    /**
     *
     * @return []
     * @see http://open.weibo.com/wiki/Oauth2/get_token_info
     * @see http://open.weibo.com/wiki/2/users/show
     */
    protected function initUserAttributes()
    {
        return $this->api('oauth2/get_token_info', 'POST');
    }

    /**
     * get UserInfo
     * @return []
     * @see http://open.weibo.com/wiki/2/users/show
     */
    public function getUserInfo()
    {
        return $this->api("2/users/show.json", 'GET', ['uid' => $this->getOpenid()]);
    }

    /**
     * @return int
     */
    public function getOpenid()
    {
        $attributes = $this->getUserAttributes();
        return $attributes['uid'];
    }

    protected function defaultName()
    {
        return 'Weibo';
    }

    protected function defaultTitle()
    {
        return 'Weibo';
    }

}
