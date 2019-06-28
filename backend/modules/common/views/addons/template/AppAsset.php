<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>\<?= $appID ?>\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\<?= $model->name;?>\<?= $appID ?>\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/<?= $model->name;?>/<?= $appID ?>/resources/';

    public $css = [
    ];

    public $js = [
    ];

    public $depends = [
    ];
}