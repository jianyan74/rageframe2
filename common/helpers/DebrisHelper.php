<?php

namespace common\helpers;

use Yii;

/**
 * Class DebrisHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class DebrisHelper
{
    /**
     * 获取水印坐标
     *
     * @param $imgUrl
     * @param $watermarkImgUrl
     * @param $point
     * @return array|bool
     */
    public static function getWatermarkLocation($imgUrl, $watermarkImgUrl, $point)
    {
        if (empty($imgUrl) || empty($watermarkImgUrl)) {
            return false;
        }

        if (!file_exists($watermarkImgUrl) || !file_exists($imgUrl)) {
            return false;
        }

        $imgSize = getimagesize($imgUrl);
        $watermarkImgSize = getimagesize($watermarkImgUrl);
        if (empty($imgSize) || empty($watermarkImgSize)) {
            return false;
        }

        $imgWidth = $imgSize[0];
        $imgHeight = $imgSize[1];
        $imgMime = $imgSize['mime'];
        $watermarkImgWidth = $watermarkImgSize[0];
        $watermarkImgHeight = $watermarkImgSize[1];
        $watermarkImgMime = $watermarkImgSize['mime'];

        switch ($point) {
            case 1 : // 左上角
                $porintLeft = 20;
                $pointTop = 20;

                break;
            case 2 : // 上中部
                $porintLeft = floor(($imgWidth - $watermarkImgWidth) / 2);
                $pointTop = 20;

                break;
            case 3 : // 右上部
                $porintLeft = $imgWidth - $watermarkImgWidth - 20;
                $pointTop = 20;

                break;
            case 4 : // 左中部
                $porintLeft = 20;
                $pointTop = floor(($imgHeight - $watermarkImgHeight) / 2);

                break;
            case 5 : // 正中部
                $porintLeft = floor(($imgWidth - $watermarkImgWidth) / 2);
                $pointTop = floor(($imgHeight - $watermarkImgHeight) / 2);

                break;
            case 6 : // 右中部
                $porintLeft = $imgWidth - $watermarkImgWidth - 20;
                $pointTop = floor(($imgHeight - $watermarkImgHeight) / 2);

                break;
            case 7 : // 左下部
                $porintLeft = 20;
                $pointTop = $imgHeight - $watermarkImgHeight - 20;

                break;
            case 8 : // 中下部
                $porintLeft = floor(($imgWidth - $watermarkImgWidth) / 2);
                $pointTop = $imgHeight - $watermarkImgHeight - 20;

                break;
            case 9 : // 右下部
                $porintLeft = $imgWidth - $watermarkImgWidth - 20;
                $pointTop = $imgHeight - $watermarkImgHeight - 20;

                break;
            default :
                return [0, 0];

                break;
        }

        // 太小就不生成水印坐标
        if (($imgWidth - $porintLeft) < $watermarkImgWidth || ($imgHeight - $pointTop) < $watermarkImgHeight) {
            return false;
        }

        return [$porintLeft, $pointTop];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public static function getUrl()
    {
        $url = explode('?', Yii::$app->request->getUrl())[0];
        $matching = '/' . Yii::$app->id . '/';
        if (substr($url, 0, strlen($matching)) == $matching) {
            $url = substr($url, strlen($matching), strlen($url));
        }

        return $url;
    }

    /**
     * 获取分页跳转
     *
     * @return array
     */
    public static function getPageSkipUrl()
    {
        $defautlUrl = Yii::$app->request->getHostInfo() . Yii::$app->request->url;
        $urlArr = explode('?', $defautlUrl);
        $defautlUrl = $urlArr[0];
        $getQueryParam = urldecode($urlArr[1] ?? '');
        $getQueryParamArr = explode('&', $getQueryParam);

        // 查询字符串是否有page
        foreach ($getQueryParamArr as $key => $value) {
            if (StringHelper::strExists($value, 'page=') && !StringHelper::strExists($value, 'per-page=')) {
                unset($getQueryParamArr[$key]);
            }
        }

        $connector = !empty($getQueryParamArr) ? '?' : '';
        $fullUrl = $defautlUrl . $connector;
        $pageConnector = '?';
        if (!empty($getQueryParamArr)) {
            $fullUrl .= implode('&', $getQueryParamArr);
            $pageConnector = '&';
        }

        return [$fullUrl, $pageConnector];
    }

    /**
     * @param $ip
     * @return string
     */
    public static function long2ip($ip)
    {
        try {
            return long2ip($ip);
        } catch (\Exception $e) {
            return $ip;
        }
    }

    /**
     * @param $ip
     * @return string
     */
    public static function analysisIp($ip, $long = true)
    {
        if (empty($ip)) {
            return false;
        }

        if (ip2long('127.0.0.1') == $ip) {
            return '本地';
        }

        if ($long === true) {
            $ip = self::long2ip($ip);
            if (((int)$ip) > 1000) {
                return '无法解析';
            }
        }

        $ipData = \Zhuzhichao\IpLocationZh\Ip::find($ip);

        $str = '';
        isset($ipData[0]) && $str .= $ipData[0];
        isset($ipData[1]) && $str .= ' · ' . $ipData[1];
        isset($ipData[2]) && $str .= ' · ' . $ipData[2];

        return $str;
    }

    /**
     * 调用这个方法前面干了什么
     *
     * @param bool $reverse
     * @return array
     */
    public static function debug($reverse = false)
    {
        $debug = debug_backtrace();
        $data = [];
        foreach ($debug as $e) {
            $function = isset($e['function']) ? $e['function'] : 'null function';
            $class = isset($e['class']) ? $e['class'] : 'null class';
            $file = isset($e['file']) ? $e['file'] : 'null file';
            $line = isset($e['line']) ? $e['line'] : 'null';
            $data[] = $file . '(' . $line . '),' . $class . '::' . $function . '()';
        }

        return $reverse == true ? array_reverse($data) : $data;
    }

    /**
     * 根据两点间的经纬度计算距离
     *
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; // 地球的近似半径(米)
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }
}