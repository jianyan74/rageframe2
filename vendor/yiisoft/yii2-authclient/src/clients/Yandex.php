<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\clients;

use yii\authclient\OAuth2;

/**
 * Yandex allows authentication via Yandex OAuth.
 *
 * In order to use Yandex OAuth you must register your application at <https://oauth.yandex.ru/client/new>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'yandex' => [
 *                 'class' => 'yii\authclient\clients\Yandex',
 *                 'clientId' => 'yandex_client_id',
 *                 'clientSecret' => 'yandex_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://oauth.yandex.ru/client/new
 * @see http://api.yandex.ru/login/doc/dg/reference/response.xml
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Yandex extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $authUrl = 'https://oauth.yandex.ru/authorize';
    /**
     * {@inheritdoc}
     */
    public $tokenUrl = 'https://oauth.yandex.ru/token';
    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://login.yandex.ru';


    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        return $this->api('info', 'GET');
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        if (!isset($data['format'])) {
            $data['format'] = 'json';
        }
        $data['oauth_token'] = $accessToken->getToken();
        $request->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'yandex';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'Yandex';
    }
}
