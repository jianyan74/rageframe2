<?php

namespace addons\Wechat\backend\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\Wechat\backend\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/Wechat/backend/resources/';

    public $css = [
        'css/wechat.css'
    ];

    public $js = [
        'js/vue.min.js',
        'js/vuedraggable.min.js',
    ];

    public $depends = [
    ];
}