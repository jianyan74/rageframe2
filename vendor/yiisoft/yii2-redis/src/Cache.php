<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\redis;

use Yii;
use yii\di\Instance;

/**
 * Redis Cache implements a cache application component based on [redis](http://redis.io/) key-value store.
 *
 * Redis Cache requires redis version 2.6.12 or higher to work properly.
 *
 * It needs to be configured with a redis [[Connection]] that is also configured as an application component.
 * By default it will use the `redis` application component.
 *
 * See [[Cache]] manual for common cache operations that redis Cache supports.
 *
 * Unlike the [[Cache]], redis Cache allows the expire parameter of [[set]], [[add]], [[mset]] and [[madd]] to
 * be a floating point number, so you may specify the time in milliseconds (e.g. 0.1 will be 100 milliseconds).
 *
 * To use redis Cache as the cache application component, configure the application as follows,
 *
 * ~~~
 * [
 *     'components' => [
 *         'cache' => [
 *             'class' => 'yii\redis\Cache',
 *             'redis' => [
 *                 'hostname' => 'localhost',
 *                 'port' => 6379,
 *                 'database' => 0,
 *             ]
 *         ],
 *     ],
 * ]
 * ~~~
 *
 * Or if you have configured the redis [[Connection]] as an application component, the following is sufficient:
 *
 * ~~~
 * [
 *     'components' => [
 *         'cache' => [
 *             'class' => 'yii\redis\Cache',
 *             // 'redis' => 'redis' // id of the connection application component
 *         ],
 *     ],
 * ]
 * ~~~
 *
 * If you have multiple redis replicas (e.g. AWS ElasticCache Redis) you can configure the cache to
 * send read operations to the replicas. If no replicas are configured, all operations will be performed on the
 * master connection configured via the [[redis]] property.
 *
 * ~~~
 * [
 *     'components' => [
 *         'cache' => [
 *             'class' => 'yii\redis\Cache',
 *             'enableReplicas' => true,
 *             'replicas' => [
 *                 // config for replica redis connections, (default class will be yii\redis\Connection if not provided)
 *                 // you can optionally put in master as hostname as well, as all GET operation will use replicas
 *                 'redis',//id of Redis [[Connection]] Component
 *                 ['hostname' => 'redis-slave-002.xyz.0001.apse1.cache.amazonaws.com'],
 *                 ['hostname' => 'redis-slave-003.xyz.0001.apse1.cache.amazonaws.com'],
 *             ],
 *         ],
 *     ],
 * ]
 * ~~~
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class Cache extends \yii\caching\Cache
{
    /**
     * @var Connection|string|array the Redis [[Connection]] object or the application component ID of the Redis [[Connection]].
     * This can also be an array that is used to create a redis [[Connection]] instance in case you do not want do configure
     * redis connection as an application component.
     * After the Cache object is created, if you want to change this property, you should only assign it
     * with a Redis [[Connection]] object.
     */
    public $redis = 'redis';
    /**
     * @var bool whether to enable read / get from redis replicas.
     * @since 2.0.8
     * @see $replicas
     */
    public $enableReplicas = false;
    /**
     * @var array the Redis [[Connection]] configurations for redis replicas.
     * Each entry is a class configuration, which will be used to instantiate a replica connection.
     * The default class is [[Connection|yii\redis\Connection]]. You should at least provide a hostname.
     *
     * Configuration example:
     *
     * ```php
     * 'replicas' => [
     *     'redis',
     *     ['hostname' => 'redis-slave-002.xyz.0001.apse1.cache.amazonaws.com'],
     *     ['hostname' => 'redis-slave-003.xyz.0001.apse1.cache.amazonaws.com'],
     * ],
     * ```
     *
     * @since 2.0.8
     * @see $enableReplicas
     */
    public $replicas = [];

    /**
     * @var Connection currently active connection.
     */
    private $_replica;


    /**
     * Initializes the redis Cache component.
     * This method will initialize the [[redis]] property to make sure it refers to a valid redis connection.
     * @throws \yii\base\InvalidConfigException if [[redis]] is invalid.
     */
    public function init()
    {
        parent::init();
        $this->redis = Instance::ensure($this->redis, Connection::className());
    }

    /**
     * Checks whether a specified key exists in the cache.
     * This can be faster than getting the value from the cache if the data is big.
     * Note that this method does not check whether the dependency associated
     * with the cached data, if there is any, has changed. So a call to [[get]]
     * may return false while exists returns true.
     * @param mixed $key a key identifying the cached value. This can be a simple string or
     * a complex data structure consisting of factors representing the key.
     * @return bool true if a value exists in cache, false if the value is not in the cache or expired.
     */
    public function exists($key)
    {
        return (bool) $this->redis->executeCommand('EXISTS', [$this->buildKey($key)]);
    }

    /**
     * @inheritdoc
     */
    protected function getValue($key)
    {
        return $this->getReplica()->executeCommand('GET', [$key]);
    }

    /**
     * @inheritdoc
     */
    protected function getValues($keys)
    {
        $response = $this->getReplica()->executeCommand('MGET', $keys);
        $result = [];
        $i = 0;
        foreach ($keys as $key) {
            $result[$key] = $response[$i++];
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function setValue($key, $value, $expire)
    {
        if ($expire == 0) {
            return (bool) $this->redis->executeCommand('SET', [$key, $value]);
        } else {
            $expire = (int) ($expire * 1000);

            return (bool) $this->redis->executeCommand('SET', [$key, $value, 'PX', $expire]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function setValues($data, $expire)
    {
        $args = [];
        foreach ($data as $key => $value) {
            $args[] = $key;
            $args[] = $value;
        }

        $failedKeys = [];
        if ($expire == 0) {
            $this->redis->executeCommand('MSET', $args);
        } else {
            $expire = (int) ($expire * 1000);
            $this->redis->executeCommand('MULTI');
            $this->redis->executeCommand('MSET', $args);
            $index = [];
            foreach ($data as $key => $value) {
                $this->redis->executeCommand('PEXPIRE', [$key, $expire]);
                $index[] = $key;
            }
            $result = $this->redis->executeCommand('EXEC');
            array_shift($result);
            foreach ($result as $i => $r) {
                if ($r != 1) {
                    $failedKeys[] = $index[$i];
                }
            }
        }

        return $failedKeys;
    }

    /**
     * @inheritdoc
     */
    protected function addValue($key, $value, $expire)
    {
        if ($expire == 0) {
            return (bool) $this->redis->executeCommand('SET', [$key, $value, 'NX']);
        } else {
            $expire = (int) ($expire * 1000);

            return (bool) $this->redis->executeCommand('SET', [$key, $value, 'PX', $expire, 'NX']);
        }
    }

    /**
     * @inheritdoc
     */
    protected function deleteValue($key)
    {
        return (bool) $this->redis->executeCommand('DEL', [$key]);
    }

    /**
     * @inheritdoc
     */
    protected function flushValues()
    {
        return $this->redis->executeCommand('FLUSHDB');
    }

    /**
     * It will return the current Replica Redis [[Connection]], and fall back to default [[redis]] [[Connection]]
     * defined in this instance. Only used in getValue() and getValues().
     * @since 2.0.8
     * @return array|string|Connection
     * @throws \yii\base\InvalidConfigException
     */
    protected function getReplica()
    {
        if ($this->enableReplicas === false) {
            return $this->redis;
        }

        if ($this->_replica !== null) {
            return $this->_replica;
        }

        if (empty($this->replicas)) {
            return $this->_replica = $this->redis;
        }

        $replicas = $this->replicas;
        shuffle($replicas);
        $config = array_shift($replicas);
        $this->_replica = Instance::ensure($config, Connection::className());
        return $this->_replica;
    }
}
