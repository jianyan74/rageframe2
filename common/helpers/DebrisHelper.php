<?php
namespace common\helpers;

use Yii;

/**
 * Class DebrisHelper
 * @package common\helpers
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
}