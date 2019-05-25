<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache\Tests\Adapter;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Traits\RedisProxy;

class RedisAdapterTest extends AbstractRedisAdapterTest
{
    public static function setupBeforeClass()
    {
        parent::setupBeforeClass();
        self::$redis = AbstractAdapter::createConnection('redis://'.getenv('REDIS_HOST'), ['lazy' => true]);
    }

    public function createCachePool($defaultLifetime = 0)
    {
        $adapter = parent::createCachePool($defaultLifetime);
        $this->assertInstanceOf(RedisProxy::class, self::$redis);

        return $adapter;
    }

    public function testCreateConnection()
    {
        $redis = RedisAdapter::createConnection('redis:?host[h1]&host[h2]&host[/foo:]');
        $this->assertInstanceOf(\RedisArray::class, $redis);
        $this->assertSame(['h1:6379', 'h2:6379', '/foo'], $redis->_hosts());
        @$redis = null; // some versions of phpredis connect on destruct, let's silence the warning

        $redisHost = getenv('REDIS_HOST');

        $redis = RedisAdapter::createConnection('redis://'.$redisHost);
        $this->assertInstanceOf(\Redis::class, $redis);
        $this->assertTrue($redis->isConnected());
        $this->assertSame(0, $redis->getDbNum());

        $redis = RedisAdapter::createConnection('redis://'.$redisHost.'/2');
        $this->assertSame(2, $redis->getDbNum());

        $redis = RedisAdapter::createConnection('redis://'.$redisHost, ['timeout' => 3]);
        $this->assertEquals(3, $redis->getTimeout());

        $redis = RedisAdapter::createConnection('redis://'.$redisHost.'?timeout=4');
        $this->assertEquals(4, $redis->getTimeout());

        $redis = RedisAdapter::createConnection('redis://'.$redisHost, ['read_timeout' => 5]);
        $this->assertEquals(5, $redis->getReadTimeout());
    }

    /**
     * @dataProvider provideFailedCreateConnection
     * @expectedException \Symfony\Component\Cache\Exception\InvalidArgumentException
     * @expectedExceptionMessage Redis connection failed
     */
    public function testFailedCreateConnection($dsn)
    {
        RedisAdapter::createConnection($dsn);
    }

    public function provideFailedCreateConnection()
    {
        return [
            ['redis://localhost:1234'],
            ['redis://foo@localhost'],
            ['redis://localhost/123'],
        ];
    }

    /**
     * @dataProvider provideInvalidCreateConnection
     * @expectedException \Symfony\Component\Cache\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid Redis DSN
     */
    public function testInvalidCreateConnection($dsn)
    {
        RedisAdapter::createConnection($dsn);
    }

    public function provideInvalidCreateConnection()
    {
        return [
            ['foo://localhost'],
            ['redis://'],
        ];
    }
}
