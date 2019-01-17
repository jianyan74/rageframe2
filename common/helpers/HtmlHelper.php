<?php
namespace common\helpers;

use common\enums\WhetherEnum;
use common\enums\StatusEnum;
use yii\helpers\BaseHtml;
use Yii;

/**
 * Class HtmlHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class HtmlHelper extends BaseHtml
{
    /**
     * 删除
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function delete($url, $content = '删除',$options = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'btn btn-danger btn-sm',
            'onclick' => "rfDelete(this);return false;"
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 编辑
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function edit($url, $content = '编辑', $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'btn btn-primary btn-sm',
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 创建
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function create($url, $content = '创建', $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => "btn btn-primary btn-xs"
        ], $options);

        $content = '<i class="fa fa-plus"></i> ' . $content;
        return self::a($content, $url, $options);
    }

    /**
     * 普通按钮
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function linkButton($url, $content, $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => "btn btn-white btn-sm"
        ], $options);

        return self::a($content, $url, $options);
    }

    /**
     * 状态标签
     *
     * @param int $status
     * @return mixed
     */
    public static function status($status = 1)
    {
        $listBut = [
            StatusEnum::DISABLED => self::tag('span', '启用', [
                'class' => "btn btn-success btn-sm",
                'onclick' => "rfStatus(this)"
            ]),
            StatusEnum::ENABLED => self::tag('span', '禁用', [
                'class' => "btn btn-default btn-sm",
                'onclick' => "rfStatus(this)"
            ]),
        ];

        return $listBut[$status] ?? '';
    }

    /**
     * 排序
     *
     * @param $value
     * @return string
     */
    public static function sort($value, $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'form-control',
            'onblur' => 'rfSort(this)',
        ], $options);

        return self::input('text', 'sort', $value, $options);
    }

    /**
     * 是否标签
     *
     * @param int $status
     * @return mixed
     */
    public static function whether($status = 1)
    {
        $listBut = [
            WhetherEnum::ENABLED => self::tag('span', '是', [
                'class' => "label label-primary label-sm",
            ]),
            StatusEnum::DISABLED => self::tag('span', '否', [
                'class' => "label label-default label-sm",
            ]),
        ];

        return $listBut[$status] ?? '';
    }

    /**
     * 头像
     *
     * @param string $head_portrait
     * @return mixed
     */
    public static function headPortrait($head_portrait)
    {
        return !empty($head_portrait) ? $head_portrait : Yii::getAlias('@web') . '/resources/dist/img/profile_small.jpg';
    }

    /**
     * 解决图片资源失败
     *
     * @return string
     */
    public static function onErrorImg()
    {
        return Yii::getAlias('@web') . '/resources/dist/img/default-head-portrait.png';
    }

    /**
     * 点击大图
     *
     * @param string $imgSrc
     * @param int $width 宽度 默认45px
     * @param int $height 高度 默认45px
     */
    public static function imageFancyBox($imgSrc, $width = 45, $height = 45)
    {
        $image = self::img($imgSrc, [
            'width' => $width,
            'height' => $height,
        ]);

        return self::a($image, $imgSrc, [
            'data-fancybox' => 'gallery'
        ]);
    }
}