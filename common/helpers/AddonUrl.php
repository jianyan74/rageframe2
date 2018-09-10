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
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toWechat(array $url, $scheme = false)
    {
        Yii::$app->set('urlManagerWechat', [
            'class' => 'yii\web\urlManager',
            'scriptUrl' => '/wechat', // 代替'baseUrl'
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'suffix' => '.html',// 静态
        ]);

        return urldecode(Yii::$app->urlManagerWechat->createAbsoluteUrl(self::regroupUrl($url), $scheme));
    }

    /**
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toFront(array $url, $scheme = false)
    {
        Yii::$app->set('urlManagerFront', [
            'class' => 'yii\web\urlManager',
            'scriptUrl' => '/index.php', // 代替'baseUrl'
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'suffix' => '.html',// 静态
        ]);

        $url = urldecode(Yii::$app->urlManagerFront->createAbsoluteUrl(self::regroupUrl($url), $scheme));
        return str_replace('index.php/', '', $url);
    }

    /**
     * @param array $url
     * @param bool $scheme
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function toApi(array $url, $scheme = false)
    {
        Yii::$app->set('urlManagerApi', [
            'class' => 'yii\web\urlManager',
            'scriptUrl' => '/api', // 代替'baseUrl'
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'suffix' => '',// 静态
        ]);

        return urldecode(Yii::$app->urlManagerApi->createAbsoluteUrl(self::regroupUrl($url), $scheme));
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