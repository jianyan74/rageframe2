<?php
namespace common\helpers;

/**
 * Class AddonHtmlHelper
 * @package common\helpers
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
            'class' => 'btn btn-warning btn-sm',
            'onclick' => "rfDelete(this);return false;"
        ], $options);

        return self::a($content, AddonUrl::to($url), $options);
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
            'class' => 'btn btn-info btn-sm',
        ], $options);

        return self::a($content, AddonUrl::to($url), $options);
    }

    /**
     * 新增
     *
     * @param $url
     * @param array $options
     * @return string
     */
    public static function create($url, $content = '新增', $options = [])
    {
        $options = ArrayHelper::merge([
            'class' => "btn btn-primary btn-xs"
        ], $options);

        $content = '<i class="fa fa-plus"></i> ' . $content;
        return self::a($content, AddonUrl::to($url), $options);
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

        return self::a($content, AddonUrl::to($url), $options);
    }
}