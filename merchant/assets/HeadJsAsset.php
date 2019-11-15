<?php

namespace merchant\assets;

use yii\web\AssetBundle;

/**
 * Class HeadJsAsset
 * @package merchant\assets
 * @author jianyan74 <751393839@qq.com>
 */
class HeadJsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/resources';

    public $js = [
        'plugins/toastr/toastr.min.js',
        'plugins/cropper/cropper.min.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}