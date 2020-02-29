<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class ImageHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class ImageHelper
{
    /**
     * 默认图片
     *
     * @param $imgSrc
     * @param string $defaultImgSre
     * @return string
     */
    public static function default($imgSrc, $defaultImgSre = '/resources/img/error.png')
    {
        return !empty($imgSrc) ? $imgSrc : Yii::getAlias('@web') . $defaultImgSre;
    }

    /**
     * 默认头像
     *
     * @param $imgSrc
     */
    public static function defaultHeaderPortrait($imgSrc, $defaultImgSre = '/resources/img/profile_small.jpg')
    {
        return !empty($imgSrc) ? $imgSrc : Yii::getAlias('@web') . $defaultImgSre;
    }

    /**
     * 点击大图
     *
     * @param string $imgSrc
     * @param int $width 宽度 默认45px
     * @param int $height 高度 默认45px
     */
    public static function fancyBox($imgSrc, $width = 45, $height = 45)
    {
        $image = Html::img($imgSrc, [
            'width' => $width,
            'height' => $height,
        ]);

        return Html::a($image, $imgSrc, [
            'data-fancybox' => 'gallery'
        ]);
    }

    /**
     * 显示图片列表
     *
     * @param $covers
     * @return string
     */
    public static function fancyBoxs($covers, $width = 45, $height = 45)
    {
        $image = '';
        if (empty($covers)) {
            return $image;
        }

        !is_array($covers) && $covers = Json::decode($covers);

        foreach ($covers as $cover) {
            $image .= Html::tag('span', self::fancyBox($cover, $width, $height), [
                'style' => 'padding-right:5px;padding-bottom:5px'
            ]);
        }

        return $image;
    }

    /**
     * 判断是否图片地址
     *
     * @param string $imgSrc
     * @return bool
     */
    public static function isImg($imgSrc)
    {
        $extend = StringHelper::clipping($imgSrc, '.', 1);

        $imgExtends = [
            'bmp',
            'jpg',
            'gif',
            'jpeg',
            'jpe',
            'jpg',
            'png',
            'jif',
            'dib',
            'rle',
            'emf',
            'pcx',
            'dcx',
            'pic',
            'tga',
            'tif',
            'tiffxif',
            'wmf',
            'jfif'
        ];
        if (in_array($extend, $imgExtends) || strpos($imgSrc, 'http://wx.qlogo.cn') !== false) {
            return true;
        }

        return false;
    }
}