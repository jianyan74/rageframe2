<?php

use yii\db\Migration;

class m190508_031101_wechat_rule_stat extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_rule_stat}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL COMMENT '商户id'",
            'rule_id' => "int(10) unsigned NULL COMMENT '规则id'",
            'rule_name' => "varchar(50) NULL DEFAULT '' COMMENT '规则名称'",
            'hit' => "int(10) unsigned NULL DEFAULT '1'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信_触发规则记录表'");
        
        /* 索引设置 */
        $this->createIndex('rid','{{%wechat_rule_stat}}','rule_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_rule_stat}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

