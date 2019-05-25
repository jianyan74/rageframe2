<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace aliyun\guzzle\subscriber;

use Psr\Http\Message\RequestInterface;

class Roa
{
    /** @var array Configuration settings */
    private $config = [
        'Version' => '2016-11-01',
        'accessKeyId' => '123456',
        'accessSecret' => '654321',
        'signatureMethod' => 'HMAC-SHA1',
        'signatureVersion' => '1.0',
        'dateTimeFormat' => 'D, d M Y H:i:s \G\M\T',
    ];

    private static $headerSeparator = "\n";

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
        $headers = $request->getHeaders();

        //prepare Header
        $headers["Date"] = gmdate($this->config['dateTimeFormat']);
        $headers["Accept"] = 'application/octet-stream';
        $headers["x-acs-signature-method"] = $this->config['signatureMethod'];
        $headers["x-acs-signature-version"] = $this->config['signatureVersion'];
        $headers['x-acs-version'] = $this->config['Version'];

        if (isset($this->config['regionId'])) {
            $headers["x-acs-region-id"] = $this->config['regionId'];
        }

        $content = $request->getBody()->getContents();
        if ($content != null) {
            $headers["Content-MD5"] = base64_encode(md5(json_encode($content), true));
        }
        $headers["Content-Type"] = "application/octet-stream;charset=utf-8";

        //compose Url

        $signString = $request->getMethod() . self::$headerSeparator;
        if (isset($headers["Accept"])) {
            $signString = $signString . $headers["Accept"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Content-MD5"])) {
            $signString = $signString . $headers["Content-MD5"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Content-Type"])) {
            $signString = $signString . $headers["Content-Type"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Date"])) {
            $signString = $signString . $headers["Date"];
        }
        $signString = $signString . self::$headerSeparator;

        $params = \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery());
        $signString = $signString . $this->buildCanonicalHeaders($headers);
        ksort($params);//参数排序
        $query = \GuzzleHttp\Psr7\build_query($params);
        $signString .= $query;

        $headers["Authorization"] = "acs " . $this->config['accessKeyId'] . ":"
            . base64_encode(hash_hmac('sha1', $signString, $this->config['accessSecret'], true));

        /** @var RequestInterface $request */
        $request = $request->withUri($request->getUri()->withQuery($query));
        foreach ($headers as $name => $val) {
            $request->withHeader($name, $val);
        }
        return $request;
    }

    /**
     * 构建规范Headers
     * @param array $headers
     * @return string
     */
    private function buildCanonicalHeaders($headers)
    {
        $sortMap = [];
        foreach ($headers as $headerKey => $headerValue) {
            $key = strtolower($headerKey);
            if (strpos($key, "x-acs-") === 0) {
                $sortMap[$key] = $headerValue;
            }
        }
        ksort($sortMap);
        $headerString = "";
        foreach ($sortMap as $sortMapKey => $sortMapValue) {
            $headerString = $headerString . $sortMapKey . ":" . $sortMapValue . self::$headerSeparator;
        }
        return $headerString;
    }
}
