<?php

use yii\db\Migration;

class m190719_024050_wechat_rule_keyword_stat extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_rule_keyword_stat}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'rule_id' => "int(10) NULL DEFAULT '0' COMMENT '规则id'",
            'keyword_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '关键字id'",
            'rule_name' => "varchar(50) NULL DEFAULT '' COMMENT '规则名称'",
            'keyword_type' => "tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类别'",
            'keyword_content' => "varchar(255) NULL DEFAULT '' COMMENT '触发的关键字内容'",
            'hit' => "int(10) unsigned NULL DEFAULT '1' COMMENT '关键字id'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_触发关键字记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_rule_keyword_stat}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

