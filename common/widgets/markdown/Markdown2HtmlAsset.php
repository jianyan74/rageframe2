<?php

namespace common\widgets\markdown;

use yii\web\AssetBundle;

/**
 * Class Markdown2HtmlAsset
 * @package common\widgets\markdown
 * @author jianyan74 <751393839@qq.com>
 */
class Markdown2HtmlAsset extends AssetBundle
{
    public $css = [
        'css/editormd.preview.css',
        'lib/fancybox/jquery.fancybox.min.css', // 图片查看
    ];

    public $js = [
        'editormd.js',
        'lib/marked.min.js',
        'lib/prettify.min.js',
        'lib/flowchart.min.js',
        'lib/jquery.flowchart.min.js',
        'lib/raphael.min.js',
        'lib/underscore.min.js',
        'lib/sequence-diagram.min.js',
        'lib/fancybox/jquery.fancybox.min.js',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}