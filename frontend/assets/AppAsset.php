<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package frontend\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        '/resources/css/site.css',
        '/resources/bower_components/font-awesome/css/font-awesome.min.css',
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'frontend\assets\HeadJsAsset',
    ];
}
