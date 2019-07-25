<?php
namespace common\widgets\ckeditor\assets;
use yii\web\AssetBundle;

/**
 * CKEditor 的asset
 * User: worry
 * Date: 2019/4/15
 * Time: 16:03
 */

class AppAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/ckeditor/resources/';
    // public $baseUrl = '/resources/plugin/ckeditor.min/';
    // css会自动载入
    public $css = [

    ];

    public $js = [
        'ckeditor.js',
    ];

    public $publishOptions = [
        // 推荐自己修改不发布。将资源复制到web目录下 修改$this->baseUrl 为可访问的地址
        // ckeditor 太大 资源不做发布管理 不然加载过慢
        // 'forceCopy' => false,
        'except' => [
            'samples/',
            'CHANGES.md',
            'LICENSE.md',
            'README.md',
        ]
    ];

    public $depends = [
    ];
}