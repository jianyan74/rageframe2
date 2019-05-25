<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\clients;

use yii\authclient\OAuth1;

/**
 * Twitter allows authentication via Twitter OAuth.
 *
 * In order to use Twitter OAuth you must register your application at <https://dev.twitter.com/apps/new>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'twitter' => [
 *                 'class' => 'yii\authclient\clients\Twitter',
 *                 'attributeParams' => [
 *                     'include_email' => 'true'
 *                 ],
 *                 'consumerKey' => 'twitter_consumer_key',
 *                 'consumerSecret' => 'twitter_consumer_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * > Note: some auth workflows provided by Twitter, such as [application-only authentication](https://dev.twitter.com/oauth/application-only),
 *   uses OAuth 2 protocol and thus are impossible to be used with this class. You should use [[TwitterOAuth2]] for these.
 *
 * @see TwitterOAuth2
 * @see https://apps.twitter.com/
 * @see https://dev.twitter.com/
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Twitter extends OAuth1
{
    /**
     * {@inheritdoc}
     */
    public $authUrl = 'https://api.twitter.com/oauth/authenticate';
    /**
     * {@inheritdoc}
     */
    public $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';
    /**
     * {@inheritdoc}
     */
    public $requestTokenMethod = 'POST';
    /**
     * {@inheritdoc}
     */
    public $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';
    /**
     * {@inheritdoc}
     */
    public $accessTokenMethod = 'POST';
    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://api.twitter.com/1.1';
    /**
     * @var array list of extra parameters, which should be used, while requesting user attributes from Twitter API.
     * For example:
     *
     * ```php
     * [
     *     'include_email' => 'true'
     * ]
     * ```
     *
     * @see https://dev.twitter.com/rest/reference/get/account/verify_credentials
     * @since 2.0.6
     */
    public $attributeParams = [];


    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        return $this->api('account/verify_credentials.json', 'GET', $this->attributeParams);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'twitter';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'Twitter';
    }
}
