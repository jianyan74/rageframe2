<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace aliyun\guzzle\subscriber;

use Psr\Http\Message\RequestInterface;

class Rpc
{
    /** @var array Configuration settings */
    private $config = [
        'Version' => '2016-11-01',
        'accessKeyId' => '123456',
        'accessSecret' => '654321',
        'signatureMethod' => 'HMAC-SHA1',
        'signatureVersion' => '1.0',
        'dateTimeFormat' => 'Y-m-d\TH:i:s\Z',
    ];

    public function __construct($config)
    {
        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->config[$key] = $value;
            }
        }
    }

    /**
     * Called when the middleware is handled.
     *
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function ($request, array $options) use ($handler) {
            $request = $this->onBefore($request);
            return $handler($request, $options);
        };
    }

    /**
     * 请求前调用
     * @param RequestInterface $request
     * @return RequestInterface
     */
    private function onBefore(RequestInterface $request)
    {
        if ($request->getMethod() == 'POST') {
            $params = [];
            parse_str($request->getBody()->getContents(), $params);
        } else {
            $params = \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery());
        }

        $params['Version'] = $this->config['Version'];
        $params['Format'] = 'JSON';
        $params['AccessKeyId'] = $this->config['accessKeyId'];
        $params['SignatureMethod'] = $this->config['signatureMethod'];
        $params['Timestamp'] = gmdate($this->config['dateTimeFormat']);
        $params['SignatureVersion'] = $this->config['signatureVersion'];
        $params['SignatureNonce'] = uniqid();
        if (isset($this->config['regionId']) && !empty($this->config['regionId'])) {//有些接口需要区域ID
            $params['RegionId'] = $this->config['regionId'];
        }
        //签名
        $params['Signature'] = $this->getSignature($request, $params);
        $body = http_build_query($params, '', '&');
        if ($request->getMethod() == 'POST') {
            $request = \GuzzleHttp\Psr7\modify_request($request, ['body' => $body]);
        } else {
            $request = \GuzzleHttp\Psr7\modify_request($request, ['query' => $body]);
        }
        return $request;
    }

    /**
     * Creates the Signature Base String.
     *
     * The Signature Base String is a consistent reproducible concatenation of
     * the request elements into a single string. The string is used as an
     * input in hashing or signing algorithms.
     *
     * @param RequestInterface $request Request being signed
     * @param array $params
     *
     * @return string Returns the base string
     */
    protected function createBaseString(RequestInterface $request, array $params)
    {
        // Remove query params from URL. Ref: Spec: 9.1.2.
        $url = $request->getUri()->withQuery('');
        $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return strtoupper($request->getMethod())
            . '&' . rawurlencode($url)
            . '&' . rawurlencode($query);
    }

    /**
     * Calculate signature for request
     *
     * @param RequestInterface $request Request to generate a signature for
     * @param array $params parameters.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getSignature(RequestInterface $request, array $params)
    {
        //参数排序
        ksort($params);
        $query = http_build_query($params, null, '&', PHP_QUERY_RFC3986);
        $source = $request->getMethod() . '&%2F&' . $this->percentEncode($query);
        return base64_encode(hash_hmac('sha1', $source, $this->config['accessSecret'] . '&', true));
    }

    protected function percentEncode($str)
    {
        $res = urlencode($str);
        $res = preg_replace('/\+/', '%20', $res);
        $res = preg_replace('/\*/', '%2A', $res);
        $res = preg_replace('/%7E/', '~', $res);
        return $res;
    }

}
