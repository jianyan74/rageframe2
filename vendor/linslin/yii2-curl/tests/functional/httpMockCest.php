<?php
//use namespaces
use Mcustiel\Phiremock\Client\Phiremock;
use Mcustiel\Phiremock\Client\Utils\A;
use Mcustiel\Phiremock\Client\Utils\Respond;
use Mcustiel\Phiremock\Client\Utils\Is;

/**
 * Class httpMockCest
 */
class httpMockCest
{
    // ############################################### private class vars // ###########################################

    /**
     * Holds instance of yii2-curl
     * @type linslin\yii2\curl\Curl
     */
    private $_curl = null;

    /**
     * Default test server endpoint URL
     * @type string
     */
    private $_endPoint = 'http://127.0.0.1:18080';


    // ################################################## class methods // #############################################

    /**
     * Cleanup
     * @param \FunctionalTester $I
     */
    public function _before(\FunctionalTester $I)
    {
        $I->haveACleanSetupInRemoteService();

        //Init curl
        $this->_curl = new linslin\yii2\curl\Curl();
    }



    /**
     * Simple HTTP ok
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function simpleHttpOkTest(\FunctionalTester $I)
    {
        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/httpStatus/200'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->get($this->_endPoint . '/test/httpStatus/200');
        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Try set params to send with get request
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setGetParamsTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/params/get?' . http_build_query($params)))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setGetParams($params)
            ->get($this->_endPoint . '/test/params/get');

        $I->assertEquals($this->_curl->responseCode, 200);
        $I->assertEquals($this->_curl->getUrl(), $this->_endPoint . '/test/params/get?' . http_build_query($params));
    }


    /**
     * Try set post to send with post request
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setPostParamsTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];


        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/x-www-form-urlencoded'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->post($this->_endPoint . '/test/params/post');
        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Try set post to send with post request
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setPostParamsOptionTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];


        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/x-www-form-urlencoded'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setOption(
            CURLOPT_POSTFIELDS,
            http_build_query($params))
            ->post($this->_endPoint . '/test/params/post');
        $I->assertEquals($this->_curl->responseCode, 200);
    }



    /**
     * Try set post param with header modification
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setPostParamsWithHeaderTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post($this->_endPoint . '/test/params/post');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Post JSON data test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function postJsonTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(json_encode($params)))
                    ->andHeader('content-type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
                    ->andBody('{"id": 1, "description": "I am a resource"}')
            )
        );

        $this->_curl->setRequestBody(json_encode($params))
            ->setHeaders([
                'content-type' => 'application/json',
                'content-length' => strlen(json_encode($params))
            ])
            ->post($this->_endPoint . '/test/params/post');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Get JSON response test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function getWithDecodedJsonResponseTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/params/get/json'))
            )->then(
                Respond::withStatusCode(200)
                    ->andBody('{"id": 1, "description": "I am a resource"}')
            )
        );

        $jsonResponse = $this->_curl->get($this->_endPoint . '/test/params/get/json', false);
        $I->assertEquals($this->_curl->responseCode, 200);
        $I->assertArrayHasKey('id', $jsonResponse);
        $I->assertArrayHasKey('description', $jsonResponse);
        $I->assertEquals($jsonResponse['id'], 1);
        $I->assertEquals($jsonResponse['description'], 'I am a resource');
    }


    /**
     * Get JSON response test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function getWithRawJsonResponseTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/params/get/json'))
            )->then(
                Respond::withStatusCode(200)
                    ->andBody('{"id": 1, "description": "I am a resource"}')
            )
        );

        $rawResponse = $this->_curl->get($this->_endPoint . '/test/params/get/json', true);
        $I->assertEquals($this->_curl->responseCode, 200);
        $I->assertEquals($rawResponse, '{"id": 1, "description": "I am a resource"}');
    }


    /**
     * Get header params with special header separators in values
     * @issue https://github.com/linslin/Yii2-Curl/issues/59
     *
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function getHeaderParamWithSpecialHeaderSeparatorInValue(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(

            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/header'))
            )->then(
                Respond::withStatusCode(200)
                    ->andHeader('param', 'value')
                    ->andHeader('location', 'http://somelocation/')
            )
        );

        $this->_curl->get($this->_endPoint . '/test/header');

        $I->assertEquals($this->_curl->responseCode, 200);
        $I->assertEquals($this->_curl->responseHeaders['location'], 'http://somelocation/');
        $I->assertEquals($this->_curl->responseHeaders['param'], 'value');
    }


    /**
     * Default head method test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultHeadMethodTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(

            Phiremock::on(
                A::headRequest()->andUrl(Is::equalTo('/test/head'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->head($this->_endPoint . '/test/head');
        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Default delete method test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultDeleteMethodTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(

            Phiremock::on(
                A::deleteRequest()->andUrl(Is::equalTo('/test/head'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->delete($this->_endPoint . '/test/head');
        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Default patch method test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultPatchMethodTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(

            Phiremock::on(
                A::patchRequest()->andUrl(Is::equalTo('/test/head'))
                    ->andHeader('X-HTTP-Method-Override', Is::equalTo('PATCH'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->patch($this->_endPoint . '/test/head');
        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Default put method test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultPutMethodTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();

        $I->expectARequestToRemoteServiceWithAResponse(

            Phiremock::on(
                A::putRequest()->andUrl(Is::equalTo('/test/head'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->put($this->_endPoint . '/test/head');
        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Set single option test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setSingleDefaultOptionTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];


        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/x-www-form-urlencoded'))
                    ->andHeader('user-agent', Is::equalTo('my-agent'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setOption(CURLOPT_USERAGENT, 'my-agent')
            ->setPostParams($params)
            ->post($this->_endPoint . '/test/params/post');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Set multiple option test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setMultipleOptionsTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];


        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/x-www-form-urlencoded'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setOptions([
                CURLOPT_USERAGENT => 'my-agent',
                CURLOPT_POSTFIELDS => http_build_query($params)
            ])
            ->post($this->_endPoint . '/test/params/post');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Set and unset option test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setUnsetSingleOptionTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(''))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setOption(CURLOPT_POSTFIELDS, http_build_query($params))
            ->unsetOption(CURLOPT_POSTFIELDS)
            ->post($this->_endPoint . '/test/params/post');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Set and unset all options test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setAllAndUnsertOptionsTest(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(''))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setOption(CURLOPT_POSTFIELDS, http_build_query($params))
            ->unsetOptions()
            ->post($this->_endPoint . '/test/params/post');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Simple reset after request test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function resetAfterGet(\FunctionalTester $I)
    {
        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/httpStatus/200'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->get($this->_endPoint . '/test/httpStatus/200');
        $I->assertEquals($this->_curl->responseCode, 200);

        $this->_curl->reset();
        $I->assertEquals($this->_curl->curl, null);
    }


    /**
     * Simple get info test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function getInfo(\FunctionalTester $I)
    {
        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/httpStatus/200'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->get($this->_endPoint . '/test/httpStatus/200');
        $I->assertEquals($this->_curl->responseCode, 200);
        $this->_curl->getInfo();
    }


    /**
     * Simple get info without curl test
     * @param \FunctionalTester $I
     */
    public function getInfoWithoutCurl(\FunctionalTester $I)
    {
        $I->assertEquals($this->_curl->getInfo(CURLINFO_HEADER_SIZE ), []);
    }


    /**
     * Simple curl timeout test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultCurlTimeoutError(\FunctionalTester $I)
    {
        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/httpStatus/timeout'))
            )->then(
                Respond::withStatusCode(404)
                    ->andDelayInMillis(2000)
            )
        );

        $this->_curl->setOption(CURLOPT_TIMEOUT, 1)
            ->get($this->_endPoint . '/test/httpStatus/timeout');

        $I->assertEquals($this->_curl->responseCode, 'timeout');
    }


    /**
     * Simple get without head request
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function simpleGetWithoutHead(\FunctionalTester $I)
    {
        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/httpStatus/200'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl
            ->setOption(CURLOPT_HEADER, false)
            ->get($this->_endPoint . '/test/httpStatus/200');

        $I->assertEquals($this->_curl->responseCode, 200);
    }


    /**
     * Simple curl error test CURL_UNSUPPORTED_PROTOCOL
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultCurlError(\FunctionalTester $I)
    {
        $this->_curl->get( 'messy:/test/httpStatus/timeout');

        $I->assertEquals($this->_curl->responseCode, null);
        $I->assertLessOrEquals($this->_curl->errorCode, 1);
    }


    /**
     * Default charset extract test
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function defaultCharsetExtractTest(\FunctionalTester $I)
    {
        $I->expectARequestToRemoteServiceWithAResponse(
            Phiremock::on(
                A::getRequest()->andUrl(Is::equalTo('/test/httpStatus/header'))
            )->then(
                Respond::withStatusCode(200)
                    ->andHeader('Content-Type', Is::equalTo('application/x-javascript;charset=UTF-8'))
            )
        );

        $this->_curl
            ->get($this->_endPoint . '/test/httpStatus/header');

        $I->assertEquals($this->_curl->responseCharset, 'utf-8');
    }


    /**
     * Try set a header param and check if getHeaders() does return it
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setHeaderParamAndTestGetHeaders(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post($this->_endPoint . '/test/params/post');


        //check for count
        $I->assertEquals(count($this->_curl->getRequestHeaders()), 1);

        //check for value
        $requestHeaders = $this->_curl->getRequestHeaders();
        $I->assertEquals($requestHeaders['Content-Type'], 'application/json');
    }


    /**
     * Try set a header param and check if getHeader() does return it
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setHeaderParamAndTestGetHeader(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post($this->_endPoint . '/test/params/post');


        //check for value
        $I->assertEquals($this->_curl->getRequestHeader('Content-Type'), 'application/json');
    }


    /**
     * Try set a single header param and check if getRequestHeader() does return it
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setSingleHeaderParamAndTestGetHeader(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeader('Content-Type', 'application/json')
            ->post($this->_endPoint . '/test/params/post');

        //check for value
        $I->assertEquals($this->_curl->getRequestHeader('Content-Type'), 'application/json');
    }


    /**
     * Try set a single header and multiple headers at once and check if getRequestHeader() does return it
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setSingleHeaderAndMultipleHeadersAndTestGetHeader(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
                    ->andHeader('custom-type', Is::equalTo('><)#7?aJEvgavJk(*4'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeader('Content-Type', 'application/json')
            ->setHeaders([
                'custom-type' => '><)#7?aJEvgavJk(*4'
            ])
            ->post($this->_endPoint . '/test/params/post');

        //check for value
        $I->assertEquals($this->_curl->getRequestHeader('Content-Type'), 'application/json');
        $I->assertEquals($this->_curl->getRequestHeader('custom-type'), '><)#7?aJEvgavJk(*4');
    }


    /**
     * Try set a single header, multiple header and unset one header param and check if getRequestHeader() does return it
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setSingleHeaderAndMultipleHeadersAndUnsetOneTillTestGetHeader(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeader('Content-Type', 'application/json')
            ->setHeaders([
                'custom-type' => '><)#7?aJEvgavJk(*4'
            ])
            ->unsetHeader('custom-type')
            ->post($this->_endPoint . '/test/params/post');

        //check for value
        $I->assertEquals($this->_curl->getRequestHeader('Content-Type'), 'application/json');
        $I->assertEquals($this->_curl->getRequestHeader('custom-type'), null);
    }


    /**
     * Try set a single header, multiple header and unset one header param and check if getRequestHeader() does return it
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setMultipleHeadersAndSingleHeaderAndUnsetOneTillTestGetHeader(\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(http_build_query($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setPostParams($params)
            ->setHeaders([
                'custom-type' => '><)#7?aJEvgavJk(*4'
            ])
            ->setHeader('Content-Type', 'application/json')
            ->unsetHeader('custom-type')
            ->post($this->_endPoint . '/test/params/post');

        //check for value
        $I->assertEquals($this->_curl->getRequestHeader('Content-Type'), 'application/json');
        $I->assertEquals($this->_curl->getRequestHeader('custom-type'), null);
    }


    /**
     * Try to post raw json string
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function setRawPostDataTest (\FunctionalTester $I)
    {
        //Init
        $this->_curl->reset();
        $params = [
            'key' => 'value',
            'secondKey' => 'secondValue'
        ];

        $I->expectARequestToRemoteServiceWithAResponse(
            $expectation = Phiremock::on(
                A::postRequest()->andUrl(Is::equalTo('/test/params/post'))
                    ->andBody(Is::equalTo(json_encode($params)))
                    ->andHeader('Content-Type', Is::equalTo('application/json'))
            )->then(
                Respond::withStatusCode(200)
            )
        );

        $this->_curl->setRawPostData(json_encode($params))
            ->setHeader('Content-Type', 'application/json')
            ->post($this->_endPoint . '/test/params/post');

        //check for value
        $I->assertEquals($this->_curl->getRequestHeader('Content-Type'), 'application/json');
    }
}
