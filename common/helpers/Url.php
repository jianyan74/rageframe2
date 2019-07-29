<?php

namespace common\helpers;

use Yii;
use yii\helpers\BaseUrl;
use common\enums\AuthEnum;

/**
 * Class Url
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Url extends BaseUrl
{
    /**
     * 生成模块Url
     *
     * @param array $url
     * @param bool $scheme
     * @return bool| string
     */
    public static function to($url = '', $scheme = false)
    {
        if (is_array($url) && Yii::$app->id != AuthEnum::TYPE_BACKEND) {
            $url = static::isMerchant($url);
        }

        if (Yii::$app->params['inAddon']) {
            return urldecode(parent::to(self::regroupUrl($url), $scheme));
        }

        return parent::to($url, $scheme);
    }

    /**
     * 生成前台链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toFront(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@frontendUrl');
        return static::create($url, $scheme, $domainName, '', 'urlManagerFront');
    }

    /**
     * 生成微信链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toWechat(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@wechatUrl');
        return static::create($url, $scheme, $domainName, '/wechat', 'urlManagerWechat');
    }

    /**
     * 生成oauth2链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toOAuth2(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@oauth2Url');
        return static::create($url, $scheme, $domainName, '/oauth2', 'urlManagerOAuth2');
    }

    /**
     * 生成oauth2链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toStorage(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@storageUrl');
        return static::create($url, $scheme, $domainName, '/storage', 'urlManagerStorage');
    }

    /**
     * 生成Api链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toApi(array $url, $scheme = false)
    {
        $domainName = Yii::getAlias('@apiUrl');
        return static::create($url, $scheme, $domainName, '/api', 'urlManagerApi');
    }

    /**
     * 获取权限所需的url
     *
     * @param $url
     * @return string
     */
    public static function getAuthUrl($url)
    {
        return static::normalizeRoute($url);
    }

    /**
     * @param $url
     * @param $scheme
     * @param $domainName
     * @param $appId
     * @param $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected static function create($url, $scheme, $domainName, $appId, $key)
    {
        $url = static::isMerchant($url);
        Yii::$app->params['inAddon'] && $url = self::regroupUrl($url);

        if (!Yii::$app->has($key)) {
            Yii::$app->set($key, [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo . $appId,
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '',// 静态
            ]);

            unset($domainName);
        }

        return urldecode(Yii::$app->$key->createAbsoluteUrl($url, $scheme));
    }

    /**
     * @param array $url
     * @return array
     */
    protected static function isMerchant(array $url)
    {
        if (true === Yii::$app->params['merchantOpen']) {
            $url = ArrayHelper::merge([
                'merchant_id' => Yii::$app->services->merchant->getId()
            ], $url);
        }

        return $url;
    }

    /**
     * 重组url
     *
     * @param array $url 重组地址
     * @param array $addonsUrl 路由地址
     * @return array
     */
    protected static function regroupUrl($url)
    {
        if (!is_array($url)) {
            return $url;
        }

        $addonsUrl = [];
        $addonsUrl[0] = '/addons/' . StringHelper::toUnderScore(Yii::$app->params['addonInfo']['name']) . '/' . self::regroupRoute($url);

        // 删除默认跳转url
        unset($url[0]);
        foreach ($url as $key => $vo) {
            $addonsUrl[$key] = $vo;
        }

        return $addonsUrl;
    }

    /**
     * 重组路由
     *
     * @param array $url
     * @return string
     */
    public static function regroupRoute($url)
    {
        $oldRoute = Yii::$app->params['addonInfo']['oldRoute'];
        $route = $url[0];
        // 如果只填写了方法转为控制器方法
        if (count(explode('/', $route)) < 2) {
            $oldRoute = explode('/', $oldRoute);
            $oldRoute[count($oldRoute) - 1] = $url[0];
            $route = implode('/', $oldRoute);

            unset($oldRoute);
        }

        return $route;
    }
}