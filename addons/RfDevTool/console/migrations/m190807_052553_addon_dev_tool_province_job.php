<?php

use yii\db\Migration;

class m190807_052553_addon_dev_tool_province_job extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_dev_tool_province_job}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'year' => "int(10) unsigned NULL DEFAULT '0' COMMENT '年份'",
            'max_level' => "tinyint(4) NULL DEFAULT '3' COMMENT '数据级别'",
            'message_id' => "int(11) NULL DEFAULT '0' COMMENT '消息id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4");
        
        /* 索引设置 */

        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_dev_tool_province_job}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

