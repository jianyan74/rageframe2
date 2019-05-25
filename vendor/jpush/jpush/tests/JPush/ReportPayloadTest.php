<?php
namespace JPush\Tests;
use PHPUnit\Framework\TestCase;

class ReportPayloadTest extends TestCase {

    protected function setUp() {
        global $client;
        $this->payload = $client->push()
                                ->setPlatform('all')
                                ->addAllAudience()
                                ->setNotificationAlert('Hello JPush');
        $this->reporter = $client->report();
    }

    public function testPusher0() {
        $payload = $this->payload;
        $response = $payload->send();

        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(2, count($body));
        $this->assertArrayHasKey('sendno', $body);
        $this->assertArrayHasKey('msg_id', $body);
        sleep(10);
        return $body['msg_id'];
    }
    public function testPusher1() {
        $payload = $this->payload;
        $response = $payload->send();
        $this->assertEquals('200', $response['http_code']);
        sleep(10);
        return $response['body']['msg_id'];
    }

    /**
     * @depends testPusher0
     * @depends testPusher1
     */
    public function testGetReceived($msg_id_0, $msg_id_1) {
        $response = $this->reporter->getReceived($msg_id_0);
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(1, count($body));
        $this->assertTrue(is_array($body[0]));
        $this->assertArrayHasKey('msg_id', $body[0]);

        $response = $this->reporter->getReceived(array($msg_id_0, $msg_id_1));
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(2, count($body));
    }

    /**
     * @depends testPusher0
     * @depends testPusher1
     */
    public function testGetMessages($msg_id_0, $msg_id_1) {
        $response = $this->reporter->getMessages($msg_id_0);
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(1, count($body));
        $this->assertTrue(is_array($body[0]));
        $this->assertEquals(4, count($body[0]));
        $this->assertArrayHasKey('msg_id', $body[0]);

        $response = $this->reporter->getMessages(array($msg_id_0, $msg_id_1));
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(2, count($body));
    }
}
