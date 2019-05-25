<?php

declare(strict_types=1);

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChatComposer\Traits;

use EasyWeChat\Kernel\Http\StreamResponse;
use EasyWeChat\Kernel\Traits\ResponseCastable;
use EasyWeChatComposer\Contracts\Encrypter;
use EasyWeChatComposer\EasyWeChat;
use EasyWeChatComposer\Encryption\DefaultEncrypter;
use EasyWeChatComposer\Exceptions\DelegationException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

trait MakesHttpRequests
{
    use ResponseCastable;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \EasyWeChatComposer\Contracts\Encrypter
     */
    protected $encrypter;

    /**
     * @param string $endpoint
     * @param array  $payload
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request($endpoint, array $payload)
    {
        $response = $this->getHttpClient()->request('POST', $endpoint, [
            'form_params' => $this->buildFormParams($payload),
        ]);

        $parsed = $this->parseResponse($response);

        return $this->detectAndCastResponseToType(
            $this->getEncrypter()->decrypt($parsed['response']),
            ($parsed['response_type'] === StreamResponse::class) ? 'raw' : $this->app['config']['response_type']
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    protected function buildFormParams($payload)
    {
        return [
            'encrypted' => $this->getEncrypter()->encrypt(json_encode($payload)),
        ];
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array
     */
    protected function parseResponse($response)
    {
        $result = json_decode((string) $response->getBody(), true);

        if (isset($result['exception'])) {
            throw (new DelegationException($result['message']))->setException($result['exception']);
        }

        return $result;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getHttpClient(): ClientInterface
    {
        return $this->httpClient ?: $this->httpClient = new Client([
            'base_uri' => $this->app['config']['delegation']['host'],
        ]);
    }

    /**
     * @return \EasyWeChatComposer\Contracts\Encrypter
     */
    protected function getEncrypter(): Encrypter
    {
        return $this->encrypter ?: $this->encrypter = new DefaultEncrypter(
            EasyWeChat::getEncryptionKey()
        );
    }
}
