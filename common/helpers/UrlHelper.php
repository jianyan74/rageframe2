<?php
namespace common\helpers;

use Yii;
use yii\helpers\BaseUrl;

/**
 * Url辅助类
 *
 * Class UrlHelper
 * @package common\helpers
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
            Yii::$app->set('urlManagerFront', [
                'class' => 'yii\web\urlManager',
                'hostInfo' => Yii::$app->request->hostInfo,
                'scriptUrl' => '', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '.html',// 静态
            ]);
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
            Yii::$app->set('urlManagerWechat', [
                'class' => 'yii\web\urlManager',
                'scriptUrl' => '/wechat', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '.html',// 静态
            ]);
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
            Yii::$app->set('urlManagerApi', [
                'class' => 'yii\web\urlManager',
                'scriptUrl' => '/api', // 代替'baseUrl'
                'enablePrettyUrl' => true,
                'showScriptName' => true,
                'suffix' => '',// 静态
            ]);
        }

        return urldecode(Yii::$app->urlManagerApi->createAbsoluteUrl($url, $scheme));
    }
}