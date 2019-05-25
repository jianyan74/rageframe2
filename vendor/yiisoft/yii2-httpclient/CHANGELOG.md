Yii Framework 2 HTTP client extension Change Log
================================================

2.0.7 September 24, 2018
------------------------

- Bug #165: `Response::detectFormatByContent` now detects JSON Array (germanow)
- Enh #156: Added `Request::setFullUrl()` return reference (vuongxuongminh)


2.0.6 February 13, 2018
-----------------------

- Bug #129: Fixed `Message::getHeaders()` unable to parse HTTP status code in case reason phrase contains `:` character (lan143)
- Enh #142: `Request::createFullUrl()` now prevents appearance of multiple slashes while combining `Client::$baseUrl` and `Request::$url` (zhangdi)


2.0.5 November 03, 2017
-----------------------

- Bug #128: Fixed `Response` with redirection takes wrong 'Content-Type' header value for content parsing (klimov-paul)
- Bug: Usage of deprecated `yii\base\Object` changed to `yii\base\BaseObject` allowing compatibility with PHP 7.2 (klimov-paul)
- Enh #119: Options for client SSL certificate specification added to `Request::$options` (bscheshirwork)
- Chg #118: Enforced cookie validation removed from `Request` (klimov-paul)


2.0.4 June 23, 2017
-------------------

- Bug #94: Fixed `XmlParser` does not respects character encoding from response headers (klimov-paul)
- Bug #98: Fixed `Request::composeCookieHeader()` no longer performs url-encoding over cookie value (klimov-paul)
- Bug #99: Fixed `Request::$content` is set to empty string by `UrlEncodedFormatter` and `JsonFormatter` from empty data (klimov-paul)
- Bug #102: Fixed `XmlParser` does not converts `\SimpleXMLElement` into array for the grouped tags (kids-return)


2.0.3 February 15, 2017
-----------------------

- Bug #74: Fixed unable to reuse `Request` instance for sending several requests with different data (klimov-paul)
- Bug #76: Fixed `HttpClientPanel` triggers `E_WARNING` on attempt to view history debug entry, generated without panel being attached (klimov-paul)
- Bug #79: Fixed inability to use URL with query parameters as `Client::$baseUrl` (klimov-paul)
- Bug #81: Fixed invalid Content-Disposition header in multipart request (cebe, PowerGamer1)
- Bug #87: Fixed `Request::addOptions()` unable to override already set CURL options (klimov-paul)
- Bug #88: Fixed `UrlEncodedFormatter` duplicates GET parameters during multiple request preparations (klimov-paul)


2.0.2 October 31, 2016
----------------------

- Bug #61: Response headers extraction at `StreamTransport` changed to use `$http_response_header` to be more reliable (klimov-paul)
- Bug #70: Fixed `Request::toString()` triggers `E_NOTICE` for not prepared request (klimov-paul)
- Bug #73: Fixed `Response::detectFormatByContent()` unable to detect URL-encoded format, if source content contains `|` symbol (klimov-paul)


2.0.1 August 04, 2016
---------------------

- Bug #44: Fixed exception name collision at `Response` and `Transport` (cebe)
- Bug #45: Fixed `XmlFormatter` unable to handle array with numeric keys (klimov-paul)
- Bug #53: Fixed `XmlParser` unable to parse value wrapped with 'CDATA' (DrDeath72)
- Bug #55: Fixed invalid display of Debug Toolbar summary block (rahimov)
- Enh #43: Events `EVENT_BEFORE_SEND` and `EVENT_AFTER_SEND` added to `Request` and `Client` (klimov-paul)
- Enh #46: Added `Request::getFullUrl()` allowing getting the full actual request URL (klimov-paul)
- Enh #47: Added `Message::addData()` allowing addition of the content data to already existing one (klimov-paul)
- Enh #50: Option 'protocolVersion' added to `Request::$options` allowing specification of the HTTP protocol version (klimov-paul)
- Enh #58: Added `UrlEncodedFormatter::$charset` allowing specification of content charset (klimov-paul)
- Enh: Added `XmlFormatter::useTraversableAsArray` allowing processing `\Traversable` as array (klimov-paul)


2.0.0.1 July 01, 2016
---------------------

- Enh: Fixed PHPdoc annotations (cebe)


2.0.0 July 1, 2016
------------------

- Initial release.
