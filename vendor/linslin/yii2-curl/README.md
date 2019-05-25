yii2-curl extension
===================
[![Latest Stable Version](https://poser.pugx.org/linslin/yii2-curl/v/stable)](https://packagist.org/packages/linslin/yii2-curl)
[![Latest Master Build](https://api.travis-ci.org/linslin/Yii2-Curl.svg?branch=master)](https://travis-ci.org/linslin/Yii2-Curl/builds)
[![Test Coverage](https://codeclimate.com/github/linslin/Yii2-Curl/badges/coverage.svg)](https://codeclimate.com/github/linslin/Yii2-Curl/coverage)
[![Total Downloads](https://poser.pugx.org/linslin/yii2-curl/downloads)](https://packagist.org/packages/linslin/yii2-curl)
[![License](https://poser.pugx.org/linslin/yii2-curl/license)](https://packagist.org/packages/linslin/yii2-curl)
                   
Easy working cURL extension for Yii2, including RESTful support:

 - POST
 - GET
 - HEAD
 - PUT
 - PATCH
 - DELETE

Requirements
------------
- Yii2
- PHP >=7.1.3
- ext-curl, ext-json, and php-curl installed


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```bash
php composer.phar require --prefer-dist linslin/yii2-curl "*"
```


Usage
-----

Once the extension is installed, simply use it in your code. The following example shows you how to handling a simple GET Request. 

```php
use linslin\yii2\curl;
$curl = new curl\Curl();

//get http://example.com/
$response = $curl->get('http://example.com/');

if ($curl->errorCode === null) {
   echo $response;
} else {
     // List of curl error codes here https://curl.haxx.se/libcurl/c/libcurl-errors.html
    switch ($curl->errorCode) {
    
        case 6:
            //host unknown example
            break;
    }
} 
```

```php
// GET request with GET params
// http://example.com/?key=value&scondKey=secondValue
$curl = new curl\Curl();
$response = $curl->setGetParams([
        'key' => 'value',
        'secondKey' => 'secondValue'
     ])
     ->get('http://example.com/');
```


```php
// POST URL form-urlencoded 
$curl = new curl\Curl();
$response = $curl->setPostParams([
        'key' => 'value',
        'secondKey' => 'secondValue'
     ])
     ->post('http://example.com/');
```

```php
// POST RAW JSON
$curl = new curl\Curl();
$response = $curl->setRawPostData(
     json_encode[
        'key' => 'value',
        'secondKey' => 'secondValue'
     ])
     ->post('http://example.com/');
```

```php
// POST RAW JSON and auto decode JSON respawn by setting raw = true. 
// This is usefull if you expect an JSON response and want to autoparse it. 
$curl = new curl\Curl();
$response = $curl->setRawPostData(
     json_encode[
        'key' => 'value',
        'secondKey' => 'secondValue'
     ])
     ->post('http://example.com/', true);
     
// JSON decoded response by parsing raw = true in to ->post().
var_dump($response);
```

```php
// POST RAW XML
$curl = new curl\Curl();
$response = $curl->setRawPostData('<?xml version="1.0" encoding="UTF-8"?><someNode>Test</someNode>')
     ->post('http://example.com/');
```


```php
// POST with special headers
$curl = new curl\Curl();
$response = $curl->setPostParams([
        'key' => 'value',
        'secondKey' => 'secondValue'
     ])
     ->setHeaders([
        'Custom-Header' => 'user-b'
     ])
     ->post('http://example.com/');
```


```php
// POST JSON with body string & special headers
$curl = new curl\Curl();

$params = [
    'key' => 'value',
    'secondKey' => 'secondValue'
];

$response = $curl->setRequestBody(json_encode($params))
     ->setHeaders([
        'Content-Type' => 'application/json',
        'Content-Length' => strlen(json_encode($params))
     ])
     ->post('http://example.com/');
```

```php
// Avanced POST request with curl options & error handling
$curl = new curl\Curl();

$params = [
    'key' => 'value',
    'secondKey' => 'secondValue'
];

$response = $curl->setRequestBody(json_encode($params))
     ->setOption(CURLOPT_ENCODING, 'gzip')
     ->post('http://example.com/');
     
// List of status codes here http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
switch ($curl->responseCode) {

    case 'timeout':
        //timeout error logic here
        break;
        
    case 200:
        //success logic here
        break;

    case 404:
        //404 Error logic here
        break;
}

//list response headers
var_dump($curl->responseHeaders);
```

Testing
------------

- Run codeception tests with `vendor/bin/codecept run` in repository root dir. 
  On windows run `vendor\bin\codecept.bat run`. 

 
Changelog
------------
##### Release 1.3.0 - Changelog
- Fixed HTTP-Method parsing on PATCH request.
- Updated DocBlocks + code refactoring.  
- Removed deprecated PHP Version support. Minimum PHP Version is now 7.1.3. 
Please use version "linslin/yii2-curl 1.2.1" - https://github.com/linslin/Yii2-Curl/releases/tag/1.2.1 if you need PHP 5.4+ support. 

##### Release 1.2.2 - Changelog
- Added some new cURL examples into readme.md.

##### Release 1.2.1 - Changelog
- Added `setRawPostData([mixed]) [this]` which allows you to post any data format. 

##### Release 1.2.0 - Changelog
- Added `unsetHeader([string header]) [this]` helper which allows you to unset one specific header.
- Added `setHeader([string header, string value]) [this]` helper which allows you to set one specific header.
- Added `getRequestHeaders() [array]` helper which returns all request headers as an array.
- Added `getRequestHeader([string headerKey]) [string|null]`  helper which returns a specific request header as an string.
- Added new test cases for `getRequestHeaders()` and `getRequestHeader()`.
- Readme adjustments. 

##### Release 1.1.3 - Changelog
- Fixed issue with patch request.
- Fully added functionalTests for 100% coverage. 

##### Release 1.1.2 - Changelog
- Fixed https://github.com/linslin/Yii2-Curl/issues/59
- Fixed https://github.com/linslin/Yii2-Curl/issues/57

##### Release 1.1.1 - Changelog
- Fixed wrong parameter parsing into `_httpRequest()` (thanks to yemexx1)
- Added JSON decode functions tests (thanks to yemexx1)

##### Release 1.1.0 - Changelog
- Added `setHeaders() [array]` helper.
- Added `setPostParams() [array]` helper.
- Added `setGetParams() [array]` helper.
- Added `setRequestBody() [string]` helper.
- Added `getUrl()` helper.
- Added API attribute `errorText [string|null]` - holds a string describing the given error code - https://github.com/linslin/Yii2-Curl/issues/49.
- Added functionTests to ensure stability.
- Allow PHP class goodness - https://github.com/linslin/Yii2-Curl/issues/52.
- Fixed header explode - https://github.com/linslin/Yii2-Curl/pull/51.

##### Release 1.0.11 - Changelog
- Added API attribute `responseHeaders [array|null]` which returns an array of all response headers. 
- Changed `_defaultOptions[CURLOPT_HEADER]` to `true`.
- Profile debugging is only active if constant `YII_DEBUG` is `true`.

##### Release 1.0.10 - Changelog
- Fixed PHP notice https://github.com/linslin/Yii2-Curl/issues/39.

##### Release 1.0.9 - Changelog
- Added API attribute `responseCode [string|null]` which holds the HTTP response code.
- Added API attribute `responseCharset [string|null]` which holds the response charset.
- Added API attribute `responseLength [integer|null]` which holds the response length.
- Added API attribute `errorCode` which holds the a integer code error like described here: https://curl.haxx.se/libcurl/c/libcurl-errors.html.
- Fixed Issue https://github.com/linslin/Yii2-Curl/issues//36.
- Fixed Issue https://github.com/linslin/Yii2-Curl/issues//37 and removed exception throwing on curl fail. This allow the user to handle the error while using attribute `errorCode`.

##### Release 1.0.8 - Changelog
- Added API method `setOptions([array])` which allows to setup multiple options at once. 
- Fixed Issue https://github.com/linslin/Yii2-Curl/issues/30.

##### Release 1.0.7 - Changelog
- Fixed `getInfo([, int $opt = 0 ])` exception were cURL wasn't initialized before calling `getInfo($opt)`.

##### Release 1.0.6 - Changelog
- Added `getInfo([, int $opt = 0 ])` method to retrieve http://php.net/manual/de/function.curl-getinfo.php data.

##### Release 1.0.5 - Changelog
- Made `body` callback not depending on HTTP-Status codes anymore. You can retrieve `body` data on any HTTP-Status now. 
- Fixed Issue https://github.com/linslin/Yii2-Curl/issues/19 where override default settings break options.
- Added timeout response handling. `$curl->responseCode = 'timeout'`

##### Release 1.0.4 - Changelog
- `CURLOPT_RETURNTRANSFER` is now set to true on default - https://github.com/linslin/Yii2-Curl/issues/18 
- Readme.md adjustments.

##### Release 1.0.3 - Changelog
- Fixed override of user options. https://github.com/linslin/Yii2-Curl/pull/7 
- Nice formatted PHP-examples. 
- Moved `parent::init();` behavior into unitTest Controller.

##### Release 1.0.2 - Changelog
- Added custom params support
- Added custom status code support
- Added POST-Param support and a readme example
- Removed "body" support at request functions. Please use "CURLOPT_POSTFIELDS" to setup a body now.
- Readme modifications

##### Release 1.0.1 - Changelog
- Removed widget support
- Edited some spellings + added more examples into readme.md

##### Release 1.0 - Changelog
- Official stable release