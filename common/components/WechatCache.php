<?php

namespace common\components;

use Yii;
use Psr\SimpleCache\CacheInterface;

/**
 * Class WechatCache
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class WechatCache implements CacheInterface
{
    /**
     * @var int
     */
    protected $setTime = 0;

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if ($data = Yii::$app->cache->get($key)) {
            return $data;
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        $this->setTime = time();

        return Yii::$app->cache->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        return Yii::$app->cache->delete($key);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return Yii::$app->cache->flush();
    }

    /**
     * @param array $keys
     * @param null $default
     * @return array|iterable
     */
    public function getMultiple($keys, $default = null)
    {
        return Yii::$app->cache->multiGet($keys) ?? $default;
    }

    /**
     * @param array $values
     * @param null $ttl
     * @return array|bool
     */
    public function setMultiple($values, $ttl = null)
    {
        $this->setTime = time();

        return Yii::$app->cache->multiSet($values, $ttl);
    }

    /**
     * @param array $keys
     * @return bool|void
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return (Yii::$app->cache->get($key) || $this->setTime > 0);
    }
}