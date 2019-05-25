<?php
namespace JPush\Tests;
use PHPUnit\Framework\TestCase;

class DevicePayloadTest extends TestCase {

    protected function setUp() {
        global $client;
        $this->device = $client->device();
        $this->test_tag = 'jpush_tag';
    }

    function testGetDevices() {
        global $registration_id;
        $response = $this->device->getDevices($registration_id);
        $this->assertEquals('200', $response['http_code']);

        echo "HTTP HEADERS ARE: ";
        print_r($response['headers']);

        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(3, count($body));
        $this->assertArrayHasKey('tags', $body);
        $this->assertArrayHasKey('alias', $body);
        $this->assertArrayHasKey('mobile', $body);
        $this->assertTrue(is_array($body['tags']));
    }

    /**
     * @expectedException \JPush\Exceptions\APIRequestException
     * @expectedExceptionCode 7002
     */
    function testGetDevicesWithInvalidRid() {
        $response = $this->device->getDevices('INVALID_REGISTRATION_ID');
    }

    function testUpdateDevicesAlias() {
        global $registration_id;
        $result = $this->device->getDevices($registration_id);
        $old_alias = $result['body']['alias'];
        if ($old_alias == null) {
            $old_alias = '';
        }
        $new_alias = 'jpush_alias';
        if ($old_alias == $new_alias) {
            $new_alias = $new_alias . time();
        }
        $response = $this->device->updateAlias($registration_id, $new_alias);
        $this->assertEquals('200', $response['http_code']);

        $response = $this->device->updateAlias($registration_id, $old_alias);
        $this->assertEquals('200', $response['http_code']);
    }

    function testUpdateDevicesTags() {
        global $registration_id;
        $new_tag = $this->test_tag;

        $response = $this->device->addTags($registration_id, array($new_tag));
        $this->assertEquals('200', $response['http_code']);

        $response = $this->device->removeTags($registration_id, array($new_tag));
        $this->assertEquals('200', $response['http_code']);
    }

    function testGetTags() {
        $response = $this->device->getTags();
        $this->assertEquals('200', $response['http_code']);

        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(1, count($body));
        $this->assertArrayHasKey('tags', $body);
    }

    function testIsDeviceInTag() {
        global $registration_id;
        $test_tag = $this->test_tag;

        $this->device->addTags($registration_id, array($test_tag));
        $response = $this->device->isDeviceInTag($registration_id, $test_tag);
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertTrue($body['result']);

        $this->device->removeTags($registration_id, array($test_tag));
        $response = $this->device->isDeviceInTag($registration_id, $test_tag);
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertFalse($body['result']);
    }

    function testUpdateTag() {
        global $registration_id;
        $test_tag = $this->test_tag;

        $response = $this->device->addDevicesToTag($test_tag, array($registration_id));
        $this->assertEquals('200', $response['http_code']);

        $response = $this->device->removeDevicesFromTag($test_tag, array($registration_id));
        $this->assertEquals('200', $response['http_code']);
    }

    function testDeleteTag() {}

    function testGetAliasDevices() {
        $test_tag = $this->test_tag;

        $response = $this->device->getAliasDevices($test_tag);
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(1, count($body));
        $this->assertArrayHasKey('registration_ids', $body);
    }

    function testDeleteAlias() {}

    function testGetDevicesStatus() {
        global $registration_id;
        $response = $this->device->getDevicesStatus($registration_id);
        $this->assertEquals('200', $response['http_code']);
        $body = $response['body'];
        $this->assertTrue(is_array($body));
        $this->assertEquals(1, count($body));
    }

}
