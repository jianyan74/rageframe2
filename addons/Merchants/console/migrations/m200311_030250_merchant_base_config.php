<?php

use yii\db\Migration;

class m200311_030250_merchant_base_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant_base_config}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'withdraw_is_open' => "tinyint(4) NULL DEFAULT '0' COMMENT '提现申请'",
            'withdraw_lowest_money' => "decimal(10,2) unsigned NULL DEFAULT '1.00' COMMENT '最低提现金额'",
            'withdraw_account' => "json NULL COMMENT '提现账户'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant_base_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

