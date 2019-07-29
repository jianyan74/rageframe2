<?php
namespace addons\RfMerchants;

use Yii;
use backend\interfaces\AddonWidget;
use yii\db\Migration;

/**
 * 卸载
 *
 * Class UnInstall
 * @package addons\RfMerchants
 */
class UnInstall extends Migration implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_merchant}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}