<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\clients;

use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\helpers\Json;

/**
 * VKontakte allows authentication via VKontakte OAuth.
 *
 * In order to use VKontakte OAuth you must register your application at <http://vk.com/editapp?act=create>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'vkontakte' => [
 *                 'class' => 'yii\authclient\clients\VKontakte',
 *                 'clientId' => 'vkontakte_client_id',
 *                 'clientSecret' => 'vkontakte_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see http://vk.com/editapp?act=create
 * @see http://vk.com/developers.php?oid=-1&p=users.get
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class VKontakte extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $authUrl = 'https://oauth.vk.com/authorize';
    /**
     * {@inheritdoc}
     */
    public $tokenUrl = 'https://oauth.vk.com/access_token';
    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://api.vk.com/method';
    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'uid',
        'first_name',
        'last_name',
        'nickname',
        'screen_name',
        'sex',
        'bdate',
        'city',
        'country',
        'timezone',
        'photo'
    ];
    /**
     * @var string the API version to send in the API request.
     * @see https://vk.com/dev/versions
     * @since 2.1.4
     */
    public $apiVersion = '5.0';


    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        $response = $this->api('users.get.json', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);

        if (empty($response['response'])) {
            throw new \RuntimeException('Unable to init user attributes due to unexpected response: ' . Json::encode($response));
        }

        $attributes = array_shift($response['response']);

        $accessToken = $this->getAccessToken();
        if (is_object($accessToken)) {
            $accessTokenParams = $accessToken->getParams();
            unset($accessTokenParams['access_token']);
            unset($accessTokenParams['expires_in']);
            $attributes = array_merge($accessTokenParams, $attributes);
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['v'] = $this->apiVersion;
        $data['user_ids'] = $accessToken->getParam('user_id');
        $data['access_token'] = $accessToken->getToken();
        $request->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'vkontakte';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'VKontakte';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'id' => 'uid'
        ];
    }
}
