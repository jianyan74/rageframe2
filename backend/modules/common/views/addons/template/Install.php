<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\<?= $model->name . "\r";?>
 */
class Install implements AddonWidget
{
    /**
    * @param $addon
    * @return mixed|void
    * @throws \yii\db\Exception
    */
    public function run($addon)
    {
        $sql = "";

        // 执行sql
        // Yii::$app->getDb()->createCommand($sql)->execute();
    }
}