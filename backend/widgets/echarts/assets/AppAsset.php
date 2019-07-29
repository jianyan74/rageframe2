<?php

namespace backend\widgets\echarts\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package backend\widgets\echarts\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{

    public $sourcePath = '@backend/widgets/echarts/resources/';

    public $css = [
    ];

    public $js = [
        'echarts.min.js',
        // 'theme/macarons.js',
        // 'theme/purple-passion.js',
        // 'theme/roma.js',
        'theme/walden.js',
        // 'theme/westeros.js',
        // 'theme/wonderland.js',
    ];
}