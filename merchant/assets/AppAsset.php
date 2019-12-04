<?php

namespace merchant\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use common\widgets\adminlet\AdminLetAsset;

/**
 * Class AppAsset
 * @package merchant\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/resources';

    public $css = [
        'plugins/toastr/toastr.min.css', // 状态通知
        'plugins/fancybox/jquery.fancybox.min.css', // 图片查看
        'plugins/cropper/cropper.min.css',
        'css/rageframe.css',
        'css/rageframe.widgets.css',
    ];

    public $js = [
        'plugins/layer/layer.js',
        'plugins/sweetalert/sweetalert.min.js',
        'plugins/fancybox/jquery.fancybox.min.js',
        'plugins/emoji/qq-wechat-emotion-parser.min.js',
        'js/template.js',
        'js/rageframe.js',
        'js/rageframe.widgets.js',
    ];

    public $depends = [
        YiiAsset::class,
        AdminLetAsset::class,
        HeadJsAsset::class
    ];
}
