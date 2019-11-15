<?php

namespace addons\RfWechat\merchant\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\RfWechat\merchant\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/RfWechat/backend/resources/';

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