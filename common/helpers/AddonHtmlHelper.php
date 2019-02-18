<?php
namespace common\helpers;

use common\enums\StatusEnum;
use Yii;

/**
 * Class AddonHtmlHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonHtmlHelper extends HtmlHelper
{
    /**
     * 删除
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function delete(array $url, $content = '删除',$options = [])
    {
        // 权限校验
        if (!self::beforVerify($url))
        {
            return '';
        }

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
    public static function edit(array $url, $content = '编辑', $options = [])
    {
        // 权限校验
        if (!self::beforVerify($url))
        {
            return '';
        }

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
    public static function create(array $url, $content = '创建', $options = [])
    {
        // 权限校验
        if (!self::beforVerify($url))
        {
            return '';
        }

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
    public static function linkButton(array $url, $content, $options = [])
    {
        // 权限校验
        if (!self::beforVerify($url))
        {
            return '';
        }

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
        // 权限校验
        if (!self::beforVerify(['ajax-update']))
        {
            return '';
        }

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
     * @param string $text
     * @param null $url
     * @param array $options
     * @return string
     */
    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null)
        {
            // 权限校验
            if (!self::beforVerify($url))
            {
                return '';
            }

            $options['href'] = AddonUrl::to($url);
        }

        return static::tag('a', $text, $options);
    }

    /**
     * @param $route
     * @return bool
     */
    protected static function beforVerify($route)
    {
        $route = AddonUrl::regroupRoute($route);
        return AddonAuthHelper::verify($route);
    }
}