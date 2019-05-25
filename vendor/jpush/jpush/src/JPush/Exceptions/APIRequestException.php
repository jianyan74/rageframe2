<?php
namespace JPush\Exceptions;

class APIRequestException extends JPushException {
    private $http_code;
    private $headers;

    private static $expected_keys = array('code', 'message');

    function __construct($response){
        $this->http_code = $response['http_code'];
        $this->headers = $response['headers'];

        $body = json_decode($response['body'], true);

        if (key_exists('error', $body)) {
            $this->code = $body['error']['code'];
            $this->message = $body['error']['message'];
        } else {
            $this->code = $body['code'];
            $this->message = $body['message'];
        }
    }

    public function __toString() {
        return "\n" . __CLASS__ . " -- [{$this->code}]: {$this->message} \n";
    }

    public function getHttpCode() {
        return $this->http_code;
    }
    public function getHeaders() {
        return $this->headers;
    }

}
