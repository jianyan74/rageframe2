<?php

namespace backend\widgets\jstree\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package backend\widgets\jstree\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@backend/widgets/jstree/resources/';

    public $css = [
        'themes/default-rage/style.min.css',
    ];

    public $js = [
        'jstree.min.js',
    ];
}