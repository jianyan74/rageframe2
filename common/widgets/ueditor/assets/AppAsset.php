<?php
namespace common\widgets\ueditor\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package common\widgets\webuploader\assets
 */
class AppAsset extends AssetBundle {

    public $sourcePath = '@common/widgets/ueditor/resources/';

    // css会自动载入
    public $css = [

    ];

    public $js = [
        // 'ueditor.all.min.js',
    ];

    public $publishOptions = [
        'except' => [
            'php/',
            'index.html',
            '.gitignore'
        ]
    ];

    public $depends = [
    ];
}