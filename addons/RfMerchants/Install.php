<?php
namespace addons\RfMerchants;

use Yii;
use backend\interfaces\AddonWidget;
use yii\db\Migration;

/**
 * 安装
 *
 * Class Install
 * @package addons\RfMerchants
 */
class Install extends Migration implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_merchant}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='插件_多商户'");

        /* 索引设置 */


        /* 表数据 */
        $this->insert('{{%addon_merchant}}',['id'=>'1','title'=>'默认商户','status'=>'1','created_at'=>'1553908350','updated_at'=>'1553908601']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
}