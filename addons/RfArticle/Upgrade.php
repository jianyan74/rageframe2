<?php
namespace addons\RfArticle;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\RfArticle */
class Upgrade implements AddonWidget
{
    /**
     * @var array
     */
    public $versions = [
        '1.0.0', // 默认版本
        '1.0.1',
        '1.0.2',
    ];

    /**
    * @param $addon
    * @return mixed|void
    */
    public function run($addon)
    {
        switch ($addon->version)
        {
            case '1.0.1' :
                // 增加测试 - 冗余的字段
                $sql = "ALTER TABLE rf_addon_example_curd ADD COLUMN redundancy_field varchar(48);";
                // Yii::$app->getDb()->createCommand($sql)->execute();
                break;
            case '1.0.2' :
                // 删除测试 - 冗余的字段
                $sql = "ALTER TABLE `rf_addon_example_curd` DROP `redundancy_field`;";
                // Yii::$app->getDb()->createCommand($sql)->execute();
                break;
        }
    }
}