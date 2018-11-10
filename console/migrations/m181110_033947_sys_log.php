<?php

use yii\db\Migration;

class m181110_033947_sys_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_log}}', [
            'id' => 'bigint(20) NOT NULL AUTO_INCREMENT',
            'level' => 'int(11) NULL',
            'category' => 'varchar(255) NULL',
            'log_time' => 'double NULL',
            'prefix' => 'text NULL',
            'message' => 'text NULL',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_日志'");
        
        /* 索引设置 */
        $this->createIndex('idx_log_level','{{%sys_log}}','level',0);
        $this->createIndex('idx_log_category','{{%sys_log}}','category',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

