<?php
namespace common\helpers;

use Yii;
use yii\helpers\Url;

/**
 * 模块Url生成辅助类
 *
 * Class AddonUrl
 * @package common\helpers
 */
class AddonUrl
{
    /**
     * 默认渲染路由名
     */
    const ADDON_EXECUTE = '/addons/execute';

    /**
     * 生成模块Url
     *
     * @param array $url
     * @param bool $scheme
     * @return bool| string
     */
    public static function to(array $url, $scheme = false)
    {
        return urldecode(Url::to(self::regroupUrl($url), $scheme));
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
        return UrlHelper::toWechat(self::regroupUrl($url), $scheme);
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
        return UrlHelper::toFront(self::regroupUrl($url), $scheme);
    }

    /**
     * 生成api链接
     *
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toApi(array $url, $scheme = false)
    {
        return UrlHelper::toApi(self::regroupUrl($url), $scheme);
    }

    /**
     * 通过绝对路径生成模块Url
     *
     * @return string
     */
    public static function toAbsoluteUrl(array $url, $scheme = false)
    {
        return urldecode(Yii::$app->urlManager->createUrl(Url::to(self::regroupUrl($url), $scheme)));
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
        $addonsUrl = [];
        $addonsUrl[0] = self::ADDON_EXECUTE;
        $addonsUrl['route'] = self::regroupRoute($url);
        $addonsUrl['addon'] = Yii::$app->params['addonInfo']['name'];

        // 删除默认跳转url
        unset($url[0]);
        foreach ($url as $key => $vo)
        {
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
    protected static function regroupRoute($url)
    {
        $oldRoute = Yii::$app->params['addonInfo']['oldRoute'];

        $route = $url[0];
        // 如果只填写了方法转为控制器方法
        if (count(explode('/',$route)) < 2)
        {
            $oldRoute = explode('/', $oldRoute);
            $oldRoute[1] = $url[0];
            $route = implode('/', $oldRoute);
        }

        return $route;
    }
}