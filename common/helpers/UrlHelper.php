<?php
namespace common\helpers;

use Yii;
use yii\helpers\BaseUrl;

/**
 * Url辅助类
 *
 * Class UrlHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class UrlHelper extends BaseUrl
{
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
        if (!Yii::$app->has('urlManagerFront'))
        {
            $domainName = Yii::getAlias('@frontendUrl');
            Yii::$app->set('urlManagerFront', [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo,
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '.html',// 静态
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
        if (!Yii::$app->has('urlManagerWechat'))
        {
            $domainName = Yii::getAlias('@wechatUrl');
            Yii::$app->set('urlManagerWechat', [
                'class' => 'yii\web\urlManager',
                'hostInfo' => !empty($domainName) ? $domainName : Yii::$app->request->hostInfo . '/wechat',
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '.html',// 静态
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
}