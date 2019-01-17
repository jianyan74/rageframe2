<?php
namespace common\helpers;

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
     * @param string $text
     * @param null $url
     * @param array $options
     * @return string
     */
    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null)
        {
            $options['href'] = AddonUrl::to($url);
        }

        return static::tag('a', $text, $options);
    }
}