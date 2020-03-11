<?php

use yii\db\Migration;

class m200311_020626_common_addons_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_addons_config}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名或标识'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'data' => "json NULL COMMENT '配置'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_插件配置值表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_addons_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

