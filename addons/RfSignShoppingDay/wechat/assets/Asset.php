<?php
namespace addons\RfSignShoppingDay\wechat\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class Asset
 * @package addons\RfSignShoppingDay\wechat\assets
 */
class Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfSignShoppingDay/resources/wechat/';

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