<?php
namespace oauth2\components;

use GuzzleHttp\Psr7\LazyOpenStream;

/**
 * Class ServerRequest
 * @package oauth2\components
 * @author jianyan74 <751393839@qq.com>
 */
class ServerRequest extends \GuzzleHttp\Psr7\ServerRequest
{
    private $attributes = [];

    /**
     * @return \GuzzleHttp\Psr7\ServerRequest|ServerRequest|\Psr\Http\Message\ServerRequestInterface
     */
    public static function fromGlobals()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $headers = getallheaders();
        $uri = self::getUriFromGlobals();
        $body = new LazyOpenStream('php://input', 'r+');
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';

        $serverRequest = new self($method, $uri, $headers, $body, $protocol, $_SERVER);

        return $serverRequest
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles(self::normalizeFiles($_FILES));
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}