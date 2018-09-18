<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web/';

    public $css = [
        'resources/css/bootstrap.min.css?v=3.3.7',
        'resources/css/font-awesome.css?v=4.7.0',
        'resources/css/animate.css',
        'resources/css/style.css?v=4.1.0',
        // 复选框样式
        'resources/css/plugins/iCheck/custom.css',
        'resources/css/plugins/iCheck/grey.css',
        // 弹出框css
        'resources/css/plugins/sweetalert/sweetalert.css?v=1',
        // rageframe自带样式
        'resources/css/rageframe.css',
        // 图片弹出css
        'resources/js/plugins/fancybox/jquery.fancybox.min.css',
    ];

    public $js = [
        // 全局js
        'resources/js/bootstrap.min.js?v=3.3.7',
        'resources/js/plugins/metisMenu/jquery.metisMenu.js',
        'resources/js/plugins/slimscroll/jquery.slimscroll.min.js',
        'resources/js/plugins/layer/layer.min.js',
        // 自定义js
        'resources/js/hplus.js?v=4.1.0',
        'resources/js/contabs.js',
        // 第三方插件
        'resources/js/plugins/pace/pace.min.js',
        // 复选框js
        'resources/js/plugins/iCheck/icheck.min.js',
        // 弹出框js
        'resources/js/plugins/sweetalert/sweetalert.min.js',
        // 图片弹出js
        'resources/js/plugins/fancybox/jquery.fancybox.min.js',
        // rageframe自带样式
        'resources/js/rageframe.js',
        // 前端模板
        'resources/js/template.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\HeadJsAsset',
    ];
}
