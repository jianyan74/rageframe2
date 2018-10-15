<?php
namespace addons\RfSignShoppingDay\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class WechatAsset
 * @package addons\RfSignShoppingDay\assets
 */
class WechatAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfSignShoppingDay/resources';

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