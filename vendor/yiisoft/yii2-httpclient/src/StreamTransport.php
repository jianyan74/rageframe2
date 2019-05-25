<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\httpclient;

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use Yii;

/**
 * StreamTransport sends HTTP messages using [Streams](http://php.net/manual/en/book.stream.php)
 *
 * For this transport, you may setup request options using [Context Options](http://php.net/manual/en/context.php)
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class StreamTransport extends Transport
{
    /**
     * {@inheritdoc}
     */
    public function send($request)
    {
        $request->beforeSend();

        $request->prepare();

        $url = $request->getFullUrl();
        $method = strtoupper($request->getMethod());

        $contextOptions = [
            'http' => [
                'method' => $method,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
            ],
        ];

        $content = $request->getContent();
        if ($content !== null) {
            $contextOptions['http']['content'] = $content;
        }
        $headers = $request->composeHeaderLines();
        $contextOptions['http']['header'] = $headers;

        $contextOptions = ArrayHelper::merge($contextOptions, $this->composeContextOptions($request->getOptions()));

        $token = $request->client->createRequestLogToken($method, $url, $headers, $content);
        Yii::info($token, __METHOD__);
        Yii::beginProfile($token, __METHOD__);

        try {
            $context = stream_context_create($contextOptions);
            $stream = fopen($url, 'rb', false, $context);
            $responseContent = stream_get_contents($stream);
            // see http://php.net/manual/en/reserved.variables.httpresponseheader.php
            $responseHeaders = $http_response_header;
            fclose($stream);
        } catch (\Exception $e) {
            Yii::endProfile($token, __METHOD__);
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        Yii::endProfile($token, __METHOD__);

        $response = $request->client->createResponse($responseContent, $responseHeaders);

        $request->afterSend($response);

        return $response;
    }

    /**
     * Composes stream context options from raw request options.
     * @param array $options raw request options.
     * @return array stream context options.
     */
    private function composeContextOptions(array $options)
    {
        $contextOptions = [];
        foreach ($options as $key => $value) {
            $section = 'http';
            if (strpos($key, 'ssl') === 0) {
                $section = 'ssl';
                $key = substr($key, 3);
            }
            $key = Inflector::underscore($key);
            $contextOptions[$section][$key] = $value;
        }
        return $contextOptions;
    }
}