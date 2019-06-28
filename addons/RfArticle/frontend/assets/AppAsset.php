<?php
namespace addons\RfArticle\frontend\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\RfArticle\frontend\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfArticle/frontend/resources/';

    public $css = [
        'plugin/bootstrap/css/bootstrap.min.css',
        'css/common.css',
    ];

    public $js = [
    ];

    public $depends = [
    ];
}