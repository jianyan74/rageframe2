<?php

namespace addons\RfArticle\html5\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\RfArticle\html5\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfArticle/html5/resources/';

    public $css = [
        'css/mescroll.min.css',
        'css/index.css',
    ];

    public $js = [
        'js/adaptation.js',
        'js/mescroll.min.js',
        'js/template-web.js',
        'js/layer/layer.js',
    ];

    public $depends = [
    ];
}