<?php
namespace addons\RfArticle\frontend\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class Asset
 * @package addons\RfArticle\frontend\assets
 */
class Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfArticle/resources/frontend/';

    public $css = [
        'plugin/bootstrap/css/bootstrap.min.css',
        'css/common.css',
    ];

    public $js = [

    ];

    public $depends = [

    ];
}