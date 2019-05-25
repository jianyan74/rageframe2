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
 * Cookie represents an HTTP cookie.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Cookie
{
    /**
     * Handles dates as defined by RFC 2616 section 3.3.1, and also some other
     * non-standard, but common formats.
     */
    private static $dateFormats = [
        'D, d M Y H:i:s T',
        'D, d-M-y H:i:s T',
        'D, d-M-Y H:i:s T',
        'D, d-m-y H:i:s T',
        'D, d-m-Y H:i:s T',
        'D M j G:i:s Y',
        'D M d H:i:s Y T',
    ];

    protected $name;
    protected $value;
    protected $expires;
    protected $path;
    protected $domain;
    protected $secure;
    protected $httponly;
    protected $rawValue;
    private $samesite;

    /**
     * Sets a cookie.
     *
     * @param string      $name         The cookie name
     * @param string      $value        The value of the cookie
     * @param string|null $expires      The time the cookie expires
     * @param string|null $path         The path on the server in which the cookie will be available on
     * @param string      $domain       The domain that the cookie is available
     * @param bool        $secure       Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param bool        $httponly     The cookie httponly flag
     * @param bool        $encodedValue Whether the value is encoded or not
     * @param string|null $samesite     The cookie samesite attribute
     */
    public function __construct(string $name, ?string $value, string $expires = null, string $path = null, string $domain = '', bool $secure = false, bool $httponly = true, bool $encodedValue = false, string $samesite = null)
    {
        if ($encodedValue) {
            $this->value = urldecode($value);
            $this->rawValue = $value;
        } else {
            $this->value = $value;
            $this->rawValue = rawurlencode($value);
        }
        $this->name = $name;
        $this->path = empty($path) ? '/' : $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
        $this->samesite = $samesite;

        if (null !== $expires) {
            $timestampAsDateTime = \DateTime::createFromFormat('U', $expires);
            if (false === $timestampAsDateTime) {
                throw new \UnexpectedValueException(sprintf('The cookie expiration time "%s" is not valid.', $expires));
            }

            $this->expires = $timestampAsDateTime->format('U');
        }
    }

    /**
     * Returns the HTTP representation of the Cookie.
     */
    public function __toString()
    {
        $cookie = sprintf('%s=%s', $this->name, $this->rawValue);

        if (null !== $this->expires) {
            $dateTime = \DateTime::createFromFormat('U', $this->expires, new \DateTimeZone('GMT'));
            $cookie .= '; expires='.str_replace('+0000', '', $dateTime->format(self::$dateFormats[0]));
        }

        if ('' !== $this->domain) {
            $cookie .= '; domain='.$this->domain;
        }

        if ($this->path) {
            $cookie .= '; path='.$this->path;
        }

        if ($this->secure) {
            $cookie .= '; secure';
        }

        if ($this->httponly) {
            $cookie .= '; httponly';
        }

        if (null !== $this->samesite) {
            $cookie .= '; samesite='.$this->samesite;
        }

        return $cookie;
    }

    /**
     * Creates a Cookie instance from a Set-Cookie header value.
     *
     * @param string      $cookie A Set-Cookie header value
     * @param string|null $url    The base URL
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString($cookie, $url = null)
    {
        $parts = explode(';', $cookie);

        if (false === strpos($parts[0], '=')) {
            throw new \InvalidArgumentException(sprintf('The cookie string "%s" is not valid.', $parts[0]));
        }

        list($name, $value) = explode('=', array_shift($parts), 2);

        $values = [
            'name' => trim($name),
            'value' => trim($value),
            'expires' => null,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'passedRawValue' => true,
            'samesite' => null,
        ];

        if (null !== $url) {
            if ((false === $urlParts = parse_url($url)) || !isset($urlParts['host'])) {
                throw new \InvalidArgumentException(sprintf('The URL "%s" is not valid.', $url));
            }

            $values['domain'] = $urlParts['host'];
            $values['path'] = isset($urlParts['path']) ? substr($urlParts['path'], 0, strrpos($urlParts['path'], '/')) : '';
        }

        foreach ($parts as $part) {
            $part = trim($part);

            if ('secure' === strtolower($part)) {
                // Ignore the secure flag if the original URI is not given or is not HTTPS
                if (!$url || !isset($urlParts['scheme']) || 'https' != $urlParts['scheme']) {
                    continue;
                }

                $values['secure'] = true;

                continue;
            }

            if ('httponly' === strtolower($part)) {
                $values['httponly'] = true;

                continue;
            }

            if (2 === \count($elements = explode('=', $part, 2))) {
                if ('expires' === strtolower($elements[0])) {
                    $elements[1] = self::parseDate($elements[1]);
                }

                $values[strtolower($elements[0])] = $elements[1];
            }
        }

        return new static(
            $values['name'],
            $values['value'],
            $values['expires'],
            $values['path'],
            $values['domain'],
            $values['secure'],
            $values['httponly'],
            $values['passedRawValue'],
            $values['samesite']
        );
    }

    private static function parseDate($dateValue)
    {
        // trim single quotes around date if present
        if (($length = \strlen($dateValue)) > 1 && "'" === $dateValue[0] && "'" === $dateValue[$length - 1]) {
            $dateValue = substr($dateValue, 1, -1);
        }

        foreach (self::$dateFormats as $dateFormat) {
            if (false !== $date = \DateTime::createFromFormat($dateFormat, $dateValue, new \DateTimeZone('GMT'))) {
                return $date->format('U');
            }
        }

        // attempt a fallback for unusual formatting
        if (false !== $date = date_create($dateValue, new \DateTimeZone('GMT'))) {
            return $date->format('U');
        }
    }

    /**
     * Gets the name of the cookie.
     *
     * @return string The cookie name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value of the cookie.
     *
     * @return string The cookie value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets the raw value of the cookie.
     *
     * @return string The cookie value
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }

    /**
     * Gets the expires time of the cookie.
     *
     * @return string|null The cookie expires time
     */
    public function getExpiresTime()
    {
        return $this->expires;
    }

    /**
     * Gets the path of the cookie.
     *
     * @return string The cookie path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Gets the domain of the cookie.
     *
     * @return string The cookie domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Returns the secure flag of the cookie.
     *
     * @return bool The cookie secure flag
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * Returns the httponly flag of the cookie.
     *
     * @return bool The cookie httponly flag
     */
    public function isHttpOnly()
    {
        return $this->httponly;
    }

    /**
     * Returns true if the cookie has expired.
     *
     * @return bool true if the cookie has expired, false otherwise
     */
    public function isExpired()
    {
        return null !== $this->expires && 0 != $this->expires && $this->expires < time();
    }

    /**
     * Gets the samesite attribute of the cookie.
     *
     * @return string|null The cookie samesite attribute
     */
    public function getSameSite(): ?string
    {
        return $this->samesite;
    }
}
