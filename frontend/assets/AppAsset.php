<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        '/resources/css/site.css',
        // 复选框样式
        '/backend/resources/css/plugins/iCheck/custom.css',
        '/backend/resources/css/plugins/iCheck/grey.css',
        // 弹出框css
        '/backend/resources/css/plugins/sweetalert/sweetalert.css?v=1',
        // 图片弹出css
        '/backend/resources/js/plugins/fancybox/jquery.fancybox.min.css',
        '/backend/resources/css/font-awesome.min.css',
    ];

    public $js = [
        // 弹出框js
        '/backend/resources/js/plugins/sweetalert/sweetalert.min.js',
        // 图片弹出js
        '/backend/resources/js/plugins/fancybox/jquery.fancybox.min.js',
        // 复选框js
        '/backend/resources/js/plugins/iCheck/icheck.min.js',
        // rageframe自带样式
        '/backend/resources/js/rageframe.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'frontend\assets\HeadJsAsset',
    ];
}
