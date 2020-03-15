<?php

use yii\db\Migration;

class m200311_020626_backend_notify_member extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%backend_notify_member}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT",
            'member_id' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id'",
            'notify_id' => "int(10) NULL DEFAULT '0' COMMENT '消息id'",
            'is_read' => "tinyint(2) NULL DEFAULT '0' COMMENT '是否已读 1已读'",
            'type' => "tinyint(1) NULL DEFAULT '0' COMMENT '消息类型[1:公告;2:提醒;3:信息(私信)'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_消息查看时间记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%backend_notify_member}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

