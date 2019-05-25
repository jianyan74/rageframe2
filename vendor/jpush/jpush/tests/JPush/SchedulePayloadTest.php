<?php
namespace JPush\Tests;
use PHPUnit\Framework\TestCase;

class SchedulePayloadTest extends TestCase {

    protected function setUp() {
        global $client;
        $this->schedule = $client->schedule();
    }

    public function testGetSchedules() {
        $schedule = $this->schedule;
        $response = $schedule->getSchedules();
        $this->assertEquals('200', $response['http_code']);
    }

}
