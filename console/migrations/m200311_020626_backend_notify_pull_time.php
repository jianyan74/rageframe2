<?php

use yii\db\Migration;

class m200311_020626_backend_notify_pull_time extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%backend_notify_pull_time}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'member_id' => "int(10) NOT NULL COMMENT '管理员id'",
            'type' => "tinyint(4) NULL DEFAULT '0' COMMENT '消息类型[1:公告;2:提醒;3:信息(私信)'",
            'alert_type' => "varchar(20) NULL DEFAULT '0' COMMENT '提醒消息类型[sys:系统;wechat:微信]'",
            'last_time' => "int(10) NULL COMMENT '最后拉取时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_消息拉取表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%backend_notify_pull_time}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

