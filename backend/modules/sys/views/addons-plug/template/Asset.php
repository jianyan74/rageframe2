<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class <?= $appID ?>Asset
 * @package addons\<?= $model->name;?>\assets
 */
class <?= $appID ?>Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/<?= $model->name;?>/resources';

    public $css = [

    ];

    public $js = [

    ];

    public $depends = [

    ];
}