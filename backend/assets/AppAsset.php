<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package backend\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web/resources';

    public $css = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
        'bower_components/font-awesome/css/font-awesome.min.css',
        'bower_components/iconfont/iconfont.css',
        'bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
        'bower_components/Ionicons/css/ionicons.min.css',
        'plugins/sweetalert/sweetalert.css', // 弹出框提示
        'plugins/fancybox/jquery.fancybox.min.css', // 图片查看
        'plugins/toastr/toastr.min.css', // 状态通知
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
        'dist/css/rageframe.css?v=2.2.2',
        'dist/css/wechat.css',
    ];

    public $js = [
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        'bower_components/fastclick/lib/fastclick.js',
        'plugins/layer/layer.min.js',
        'plugins/sweetalert/sweetalert.min.js',
        'plugins/fancybox/jquery.fancybox.min.js',
        'dist/js/adminlte.js',
        'dist/js/demo.js',
        'dist/js/contabs.js',
        'dist/js/content.js',
        'dist/js/template.js',
        'dist/js/rageframe.js?v=2.2.2',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'backend\assets\HeadJsAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
