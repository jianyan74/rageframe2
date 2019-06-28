<?php
namespace addons\RfSignShoppingDay\wechat\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\RfSignShoppingDay\wechat\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfSignShoppingDay/wechat/resources/';

    public $css = [
        'css/index.css',
    ];

    public $js = [
        'js/zepto.min.js',
        'js/audio.js',
        'js/template.js',
        'js/index.js',
    ];

    public $depends = [
    ];
}