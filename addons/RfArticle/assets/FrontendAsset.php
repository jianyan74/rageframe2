<?php
namespace addons\RfArticle\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class FrontendAsset
 * @package addons\RfArticle\assets
 */
class FrontendAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfArticle/resources';

    public $css = [
        'plugin/bootstrap/css/bootstrap.min.css',
        'css/common.css',
    ];

    public $js = [

    ];

    public $depends = [

    ];
}