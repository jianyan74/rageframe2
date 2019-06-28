<?php
namespace addons\RfMerchants;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 卸载
 *
 * Class UnInstall
 * @package addons\RfMerchants
 */
class UnInstall implements AddonWidget
{
    /**
     * 表前缀
     *
     * @var string
     */
    protected $table_prefixion = "rf_addon_";

    /**
     * 列表
     *
     * @var array
     */
    protected $table_name = ['merchant'];

    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        $sql = "";
        foreach ($this->table_name as $value) {
            $table = $this->table_prefixion . $value;
            $sql .= "DROP TABLE IF EXISTS `{$table}`;";
        }

        // 执行sql
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}