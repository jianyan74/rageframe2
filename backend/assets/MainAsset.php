<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class MainAsset
 * @package backend\assets
 * @author jianyan74 <751393839@qq.com>
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web/resources';

    public $css = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
        'bower_components/font-awesome/css/font-awesome.min.css',
        'bower_components/Ionicons/css/ionicons.min.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
        'dist/css/rageframe.css',
    ];

    public $js = [
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        'bower_components/fastclick/lib/fastclick.js',
        'plugins/layer/layer.js',
        'dist/js/adminlte.js',
        'dist/js/demo.js',
        'dist/js/contabs.js',
        'dist/js/rageframe.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'backend\assets\CompatibilityIEAsset',
        'backend\assets\HeadJsAsset',
    ];
}