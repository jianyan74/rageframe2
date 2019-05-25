<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace aliyun\test;


class ClientTest extends TestCase
{

    public function testExpirationTime()
    {
        $this->client->setExpirationTime(1488966279);
        $this->assertEquals(1488966279, $this->client->getExpirationTime());
    }

    public function testSign()
    {
        $this->client->setExpirationTime(1488966279);
        $sign = $this->client->getSign('123456');

        $this->assertTrue($this->client->checkSign('123456',substr($sign,1)));

        $sign = $this->client->getSign('123456');
        $this->assertFalse($this->client->checkSign('1234567',$sign));

    }
}
