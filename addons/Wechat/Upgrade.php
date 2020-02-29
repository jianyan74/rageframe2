<?php

namespace addons\Wechat;

use Yii;
use yii\db\Migration;
use common\interfaces\AddonWidget;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\Wechat */
class Upgrade extends Migration implements AddonWidget
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
    * @throws \yii\db\Exception
    */
    public function run($addon)
    {
        switch ($addon->version) {
            case '1.0.1' :
                // 增加测试 - 冗余的字段
                // $this->addColumn('{{%addon_example_curd}}', 'redundancy_field', 'varchar(48)');
                break;
            case '1.0.2' :
                // 删除测试 - 冗余的字段
                // $this->dropColumn('{{%addon_example_curd}}', 'redundancy_field');
                break;
        }
    }
}