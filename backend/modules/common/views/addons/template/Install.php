<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>;

use Yii;
use yii\db\Migration;
use common\helpers\MigrateHelper;
use common\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\<?= $model->name . "\r";?>
 */
class Install extends Migration implements AddonWidget
{
    /**
    * @param $addon
    * @return mixed|void
    * @throws \yii\base\InvalidConfigException
    * @throws \yii\web\NotFoundHttpException
    * @throws \yii\web\UnprocessableEntityHttpException
    */
    public function run($addon)
    {
        // MigrateHelper::upByPath([
        //     '@addons/<?= $model->name;?>/console/migrations/'
        // ]);
    }
}