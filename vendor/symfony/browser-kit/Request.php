<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\BrowserKit;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Request
{
    protected $uri;
    protected $method;
    protected $parameters;
    protected $files;
    protected $cookies;
    protected $server;
    protected $content;

    /**
     * @param string $uri        The request URI
     * @param string $method     The HTTP method request
     * @param array  $parameters The request parameters
     * @param array  $files      An array of uploaded files
     * @param array  $cookies    An array of cookies
     * @param array  $server     An array of server parameters
     * @param string $content    The raw body data
     */
    public function __construct(string $uri, string $method, array $parameters = [], array $files = [], array $cookies = [], array $server = [], string $content = null)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->files = $files;
        $this->cookies = $cookies;
        $this->server = $server;
        $this->content = $content;
    }

    /**
     * Gets the request URI.
     *
     * @return string The request URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Gets the request HTTP method.
     *
     * @return string The request HTTP method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the request parameters.
     *
     * @return array The request parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Gets the request server files.
     *
     * @return array The request files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Gets the request cookies.
     *
     * @return array The request cookies
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Gets the request server parameters.
     *
     * @return array The request server parameters
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Gets the request raw body data.
     *
     * @return string The request raw body data
     */
    public function getContent()
    {
        return $this->content;
    }
}
