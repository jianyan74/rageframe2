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
class Response
{
    protected $content;
    protected $status;
    protected $headers;

    /**
     * The headers array is a set of key/value pairs. If a header is present multiple times
     * then the value is an array of all the values.
     *
     * @param string $content The content of the response
     * @param int    $status  The response status code
     * @param array  $headers An array of headers
     */
    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * Converts the response object to string containing all headers and the response content.
     *
     * @return string The response with headers and content
     */
    public function __toString()
    {
        $headers = '';
        foreach ($this->headers as $name => $value) {
            if (\is_string($value)) {
                $headers .= $this->buildHeader($name, $value);
            } else {
                foreach ($value as $headerValue) {
                    $headers .= $this->buildHeader($name, $headerValue);
                }
            }
        }

        return $headers."\n".$this->content;
    }

    /**
     * Returns the build header line.
     *
     * @param string $name  The header name
     * @param string $value The header value
     *
     * @return string The built header line
     */
    protected function buildHeader($name, $value)
    {
        return sprintf("%s: %s\n", $name, $value);
    }

    /**
     * Gets the response content.
     *
     * @return string The response content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gets the response status code.
     *
     * @return int The response status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the response headers.
     *
     * @return array The response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets a response header.
     *
     * @param string $header The header name
     * @param bool   $first  Whether to return the first value or all header values
     *
     * @return string|array The first header value if $first is true, an array of values otherwise
     */
    public function getHeader($header, $first = true)
    {
        $normalizedHeader = str_replace('-', '_', strtolower($header));
        foreach ($this->headers as $key => $value) {
            if (str_replace('-', '_', strtolower($key)) === $normalizedHeader) {
                if ($first) {
                    return \is_array($value) ? (\count($value) ? $value[0] : '') : $value;
                }

                return \is_array($value) ? $value : [$value];
            }
        }

        return $first ? null : [];
    }
}
