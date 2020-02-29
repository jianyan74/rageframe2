<?php

namespace common\components;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\enums\CacheEnum;

/**
 * Class Debris
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Debris
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * 返回配置名称
     *
     * @param string $name 字段名称
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @param string $merchant_id
     * @return string|null
     */
    public function config($name, $noCache = false, $merchant_id = 1)
    {
        // 获取缓存信息
        $info = $this->getConfigInfo($noCache, $merchant_id);

        return isset($info[$name]) ? trim($info[$name]) : null;
    }

    /**
     * 返回配置名称
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|bool|mixed
     */
    public function configAll($noCache = false, $merchant_id = 1)
    {
        $info = $this->getConfigInfo($noCache, $merchant_id);
        return $info ? $info : [];
    }

    /**
     * 获取当前商户配置
     *
     * @param $name
     * @param bool $noCache
     * @return string|null
     */
    public function merchantConfig($name, $noCache = false)
    {
        return $this->config($name, $noCache, Yii::$app->services->merchant->getId());
    }

    /**
     * 获取当前商户的全部配置
     *
     * @param bool $noCache
     * @return array|bool|mixed
     */
    public function merchantConfigAll($noCache = false)
    {
        return $this->configAll($noCache, Yii::$app->services->merchant->getId());
    }

    /**
     * 获取全部配置信息
     *
     * @param $noCache true 不从缓存读取 false 从缓存读取
     * @param int $merchant_id 强制从某个商户读取
     * @return array|mixed
     */
    protected function getConfigInfo($noCache, $merchant_id)
    {
        if ($noCache == false && $this->config) {
            return $this->config;
        }

        // 获取缓存信息
        $cacheKey = CacheEnum::getPrefix('config', $merchant_id);
        if ($noCache == true || !($this->config = Yii::$app->cache->get($cacheKey))) {
            $config = Yii::$app->services->config->findAllWithValue($merchant_id);
            $this->config = [];

            foreach ($config as $row) {
                $this->config[$row['name']] = $row['value']['data'] ?? $row['default_value'];
            }

            // 设置缓存
            Yii::$app->cache->set($cacheKey, $this->config, 60 * 60);
        }

        return $this->config;
    }

    /**
     * 获取设备客户端信息
     *
     * @return mixed|string
     */
    public function detectVersion()
    {
        /** @var \Detection\MobileDetect $detect */
        $detect = Yii::$app->mobileDetect;
        if ($detect->isMobile()) {
            $devices = $detect->getOperatingSystems();
            $device = '';

            foreach ($devices as $key => $valaue) {
                if ($detect->is($key)) {
                    $device = $key . $detect->version($key);
                    break;
                }
            }

            return $device;
        }

        return $detect->getUserAgent();
    }

    /**
     * 打印
     *
     * @param mixed ...$array
     */
    public function p(...$array)
    {
        echo "<pre>";

        if (count($array) == 1) {
            print_r($array[0]);
        } else {
            print_r($array);
        }

        echo '</pre>';
    }

    /**
     * 解析微信是否报错
     *
     * @param array $message 微信回调数据
     * @param bool $direct 是否直接报错
     * @return bool
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getWechatError($message, $direct = true)
    {
        if (isset($message['errcode']) && $message['errcode'] != 0) {
            // token过期 强制重新从微信服务器获取 token.
            if ($message['errcode'] == 40001) {
                Yii::$app->wechat->app->access_token->getToken(true);
            }

            if ($direct) {
                throw new UnprocessableEntityHttpException($message['errmsg']);
            }

            return $message['errmsg'];
        }

        return false;
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    public function analyErr($firstErrors)
    {
        if (!is_array($firstErrors) || empty($firstErrors)) {
            return false;
        }

        $errors = array_values($firstErrors)[0];
        return $errors ?? '未捕获到错误信息';
    }
}