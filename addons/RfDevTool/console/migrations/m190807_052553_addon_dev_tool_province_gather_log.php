<?php

use yii\db\Migration;

class m190807_052553_addon_dev_tool_province_gather_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_dev_tool_province_gather_log}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'job_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '工作id'",
            'message_id' => "int(11) NULL DEFAULT '0' COMMENT '消息id'",
            'data' => "json NULL COMMENT '数据'",
            'url' => "varchar(200) NULL",
            'max_level' => "tinyint(4) NULL COMMENT '最大级别'",
            'level' => "tinyint(4) NULL COMMENT '当前级别'",
            'reconnection' => "tinyint(4) NULL COMMENT '重连次数'",
            'remark' => "varchar(200) NULL COMMENT '备注'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '更新时间'",
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
        $this->dropTable('{{%addon_dev_tool_province_gather_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

