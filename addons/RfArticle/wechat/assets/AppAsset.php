<?php
namespace addons\RfArticle\wechat\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\RfArticle\wechat\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfArticle/wechat/resources/';

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