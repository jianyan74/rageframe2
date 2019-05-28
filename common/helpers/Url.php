<?php
namespace common\helpers;

use Yii;
use yii\helpers\BaseUrl;

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
        if (Yii::$app->params['inAddon'])
        {
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
        Yii::$app->params['inAddon'] && $url = self::regroupUrl($url);

        if (!Yii::$app->has('urlManagerFront'))
        {
            $domainName = Yii::getAlias('@frontendUrl');
            Yii::$app->set('urlManagerFront', [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo,
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                // 'suffix' => '.html',// 静态
            ]);

            unset($domainName);
        }

        return urldecode(Yii::$app->urlManagerFront->createAbsoluteUrl($url, $scheme));
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
        Yii::$app->params['inAddon'] && $url = self::regroupUrl($url);

        if (!Yii::$app->has('urlManagerWechat'))
        {
            $domainName = Yii::getAlias('@wechatUrl');
            Yii::$app->set('urlManagerWechat', [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo . '/wechat',
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                // 'suffix' => '.html',// 静态
            ]);

            unset($domainName);
        }

        return urldecode(Yii::$app->urlManagerWechat->createAbsoluteUrl($url, $scheme));
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
        Yii::$app->params['inAddon'] && $url = self::regroupUrl($url);

        if (!Yii::$app->has('urlManagerApi'))
        {
            $domainName = Yii::getAlias('@apiUrl');
            Yii::$app->set('urlManagerApi', [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo . '/api',
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '',// 静态
            ]);

            unset($domainName);
        }

        return urldecode(Yii::$app->urlManagerApi->createAbsoluteUrl($url, $scheme));
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
     * 重组url
     *
     * @param array $url 重组地址
     * @param array $addonsUrl 路由地址
     * @return array
     */
    protected static function regroupUrl($url)
    {
        if (!is_array($url))
        {
            return $url;
        }

        $addonsUrl = [];
        $addonsUrl[0] = '/addons/execute';
        $addonsUrl['route'] = self::regroupRoute($url);
        $addonsUrl['addon'] = StringHelper::toUnderScore(Yii::$app->params['addonInfo']['name']);

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
    public static function regroupRoute($url)
    {
        if (empty($url))
        {
            return '';
        }

        $oldRoute = Yii::$app->params['addonInfo']['oldRoute'];

        $route = $url[0];
        // 如果只填写了方法转为控制器方法
        if (count(explode('/', $route)) < 2)
        {
            $oldRoute = explode('/', $oldRoute);
            $oldRoute[count($oldRoute) - 1] = $url[0];
            $route = implode('/', $oldRoute);

            unset($oldRoute);
        }

        return $route;
    }
}