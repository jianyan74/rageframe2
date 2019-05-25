<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DomCrawler;

/**
 * Any HTML element that can link to an URI.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class AbstractUriElement
{
    /**
     * @var \DOMElement
     */
    protected $node;

    /**
     * @var string The method to use for the element
     */
    protected $method;

    /**
     * @var string The URI of the page where the element is embedded (or the base href)
     */
    protected $currentUri;

    /**
     * @param \DOMElement $node       A \DOMElement instance
     * @param string      $currentUri The URI of the page where the link is embedded (or the base href)
     * @param string      $method     The method to use for the link (GET by default)
     *
     * @throws \InvalidArgumentException if the node is not a link
     */
    public function __construct(\DOMElement $node, string $currentUri = null, ?string $method = 'GET')
    {
        $this->setNode($node);
        $this->method = $method ? strtoupper($method) : null;
        $this->currentUri = $currentUri;

        $elementUriIsRelative = null === parse_url(trim($this->getRawUri()), PHP_URL_SCHEME);
        $baseUriIsAbsolute = \in_array(strtolower(substr($this->currentUri, 0, 4)), ['http', 'file']);
        if ($elementUriIsRelative && !$baseUriIsAbsolute) {
            throw new \InvalidArgumentException(sprintf('The URL of the element is relative, so you must define its base URI passing an absolute URL to the constructor of the %s class ("%s" was passed).', __CLASS__, $this->currentUri));
        }
    }

    /**
     * Gets the node associated with this link.
     *
     * @return \DOMElement A \DOMElement instance
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Gets the method associated with this link.
     *
     * @return string The method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the URI associated with this link.
     *
     * @return string The URI
     */
    public function getUri()
    {
        $uri = trim($this->getRawUri());

        // absolute URL?
        if (null !== parse_url($uri, PHP_URL_SCHEME)) {
            return $uri;
        }

        // empty URI
        if (!$uri) {
            return $this->currentUri;
        }

        // an anchor
        if ('#' === $uri[0]) {
            return $this->cleanupAnchor($this->currentUri).$uri;
        }

        $baseUri = $this->cleanupUri($this->currentUri);

        if ('?' === $uri[0]) {
            return $baseUri.$uri;
        }

        // absolute URL with relative schema
        if (0 === strpos($uri, '//')) {
            return preg_replace('#^([^/]*)//.*$#', '$1', $baseUri).$uri;
        }

        $baseUri = preg_replace('#^(.*?//[^/]*)(?:\/.*)?$#', '$1', $baseUri);

        // absolute path
        if ('/' === $uri[0]) {
            return $baseUri.$uri;
        }

        // relative path
        $path = parse_url(substr($this->currentUri, \strlen($baseUri)), PHP_URL_PATH);
        $path = $this->canonicalizePath(substr($path, 0, strrpos($path, '/')).'/'.$uri);

        return $baseUri.('' === $path || '/' !== $path[0] ? '/' : '').$path;
    }

    /**
     * Returns raw URI data.
     *
     * @return string
     */
    abstract protected function getRawUri();

    /**
     * Returns the canonicalized URI path (see RFC 3986, section 5.2.4).
     *
     * @param string $path URI path
     *
     * @return string
     */
    protected function canonicalizePath($path)
    {
        if ('' === $path || '/' === $path) {
            return $path;
        }

        if ('.' === substr($path, -1)) {
            $path .= '/';
        }

        $output = [];

        foreach (explode('/', $path) as $segment) {
            if ('..' === $segment) {
                array_pop($output);
            } elseif ('.' !== $segment) {
                $output[] = $segment;
            }
        }

        return implode('/', $output);
    }

    /**
     * Sets current \DOMElement instance.
     *
     * @param \DOMElement $node A \DOMElement instance
     *
     * @throws \LogicException If given node is not an anchor
     */
    abstract protected function setNode(\DOMElement $node);

    /**
     * Removes the query string and the anchor from the given uri.
     */
    private function cleanupUri(string $uri): string
    {
        return $this->cleanupQuery($this->cleanupAnchor($uri));
    }

    /**
     * Remove the query string from the uri.
     */
    private function cleanupQuery(string $uri): string
    {
        if (false !== $pos = strpos($uri, '?')) {
            return substr($uri, 0, $pos);
        }

        return $uri;
    }

    /**
     * Remove the anchor from the uri.
     */
    private function cleanupAnchor(string $uri): string
    {
        if (false !== $pos = strpos($uri, '#')) {
            return substr($uri, 0, $pos);
        }

        return $uri;
    }
}
