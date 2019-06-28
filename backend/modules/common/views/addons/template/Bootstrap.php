<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>\common\config;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\<?= $model->name;?>\common\config<?= "\r";?>
 */
class Bootstrap implements AddonWidget
{
    /**
    * @param $addon
    * @return mixed|void
    */
    public function run($addon)
    {

    }
}