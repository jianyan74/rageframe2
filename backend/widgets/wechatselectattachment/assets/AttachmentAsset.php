<?php
namespace backend\widgets\wechatselectattachment\assets;

use yii\web\AssetBundle;

/**
 * Class AttachmentAsset
 * @package backend\widgets\wechatselectattachment\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/wechatselectattachment/resources/';

    public $css = [
    ];

    public $js = [
        'list.js',
    ];

    public $depends = [
    ];
}