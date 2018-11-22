<?php
namespace common\widgets\webuploader\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package common\widgets\webuploader\assets
 */
class AppAsset extends AssetBundle {

    public $sourcePath = '@common/widgets/webuploader/resources/';

    public $css = [
        'css/common.css',
    ];

    public $js = [
        'js/uploader.js',
        'js/Sortable.min.js'
    ];

    public $depends = [

    ];
}