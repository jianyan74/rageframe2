<?php

namespace backend\widgets\color\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package backend\widgets\color\assets
 * @author kbdxbt
 */
class AppAsset extends AssetBundle
{

    public $sourcePath = '@backend/widgets/color/resources/';

    public $css = [
        'css/colpick.css'
    ];

    public $js = [
        'js/colpick.js',
    ];
}