<?php
namespace common\components;

use Yii;
use common\models\sys\Config;
use yii\web\UnprocessableEntityHttpException;

/**
 * 碎片组件
 *
 * Class Debris
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Debris
{
    const CACHE_PREFIX = 'backendSysConfig'; // 缓存前缀

    /**
     * 返回配置名称
     *
     * @param string $name 字段名称
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return bool|string
     */
    public function config($name, $noCache = false)
    {
        // 获取缓存信息
        $info = $this->getConfigInfo($noCache);
        return isset($info[$name]) ? trim($info[$name]) : null;
    }

    /**
     * 返回配置名称
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|bool|mixed
     */
    public function configAll($noCache = false)
    {
        $info = $this->getConfigInfo($noCache);
        return $info ? $info : [];
    }

    /**
     * 清除缓存
     */
    public function clearConfigCache()
    {
        Yii::$app->cache->delete(self::CACHE_PREFIX);
    }

    /**
     * 获取全部配置信息
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|mixed
     */
    protected function getConfigInfo($noCache)
    {
        // 获取缓存信息
        $cacheKey = self::CACHE_PREFIX;
        if (!($info = Yii::$app->cache->get($cacheKey)) || $noCache == true)
        {
            $info = Config::getList();
            // 设置缓存
            Yii::$app->cache->set($cacheKey, $info, 60 * 60);
        }

        return $info;
    }

    /**
     * 打印
     *
     * @param $array
     */
    public function p($array)
    {
        echo "<pre>";
        print_r($array);
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
        if (isset($message['errcode']) && $message['errcode'] != 0)
        {
            // token过期 强制重新从微信服务器获取 token.
            if ($message['errcode'] == 40001)
            {
                Yii::$app->wechat->app->access_token->getToken(true);
            }

            if ($direct)
            {
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
        if (!is_array($firstErrors) || empty($firstErrors))
        {
            return false;
        }

        $errors = array_values($firstErrors)[0];

        return $errors ?? '未捕获到错误信息';
    }
}