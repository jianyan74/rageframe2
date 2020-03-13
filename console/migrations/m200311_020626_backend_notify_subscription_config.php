<?php

use yii\db\Migration;

class m200311_020626_backend_notify_subscription_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%backend_notify_subscription_config}}', [
            'member_id' => "int(10) unsigned NOT NULL COMMENT '用户id'",
            'action' => "json NULL COMMENT '订阅事件'",
            'PRIMARY KEY (`member_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_消息配置表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%backend_notify_subscription_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

