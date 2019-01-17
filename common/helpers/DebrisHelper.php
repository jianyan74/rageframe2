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
        if (empty($imgUrl) || empty($watermarkImgUrl))
        {
            return false;
        }

        if (!file_exists($watermarkImgUrl) || !file_exists($imgUrl))
        {
            return false;
        }

        $imgSize = getimagesize($imgUrl);
        $watermarkImgSize = getimagesize($watermarkImgUrl);
        if (empty($imgSize) || empty($watermarkImgSize))
        {
            return false;
        }

        $imgWidth = $imgSize[0];
        $imgHeight = $imgSize[1];
        $imgMime = $imgSize['mime'];
        $watermarkImgWidth = $watermarkImgSize[0];
        $watermarkImgHeight = $watermarkImgSize[1];
        $watermarkImgMime = $watermarkImgSize['mime'];

        switch ($point)
        {
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
        if (($imgWidth - $porintLeft) < $watermarkImgWidth || ($imgHeight - $pointTop) < $watermarkImgHeight)
        {
            return false;
        }

        return [$porintLeft, $pointTop];
    }

    /**
     * 判断是否手机端
     *
     * @return bool
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }

        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }

        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }

        return false;
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
        foreach($debug as $e)
        {
            $function = isset($e['function']) ? $e['function'] : 'null function';
            $class = isset($e['class']) ? $e['class'] : 'null class';
            $file = isset($e['file']) ? $e['file'] : 'null file';
            $line = isset($e['line']) ? $e['line'] : 'null';
            $data[] = $file . '(' . $line . '),' . $class . '::' . $function . '()';
        }

        return $reverse == true ? array_reverse($data) : $data;
    }
}