<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class CompatibilityIEAsset
 * @package backend\assets
 * @author jianyan74 <751393839@qq.com>
 */
class CompatibilityIEAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/resources';

    public $js = [
        'dist/js/html5shiv.min.js',
        'dist/js/respond.min.js',
    ];

    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position' => \yii\web\View::POS_HEAD
    ];
}