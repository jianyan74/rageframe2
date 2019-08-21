<?php

use yii\db\Migration;

class m190821_022131_sys_notify_subscription_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_notify_subscription_config}}', [
            'manager_id' => "int(10) unsigned NOT NULL COMMENT '用户id'",
            'action' => "json NULL COMMENT '订阅事件'",
            'PRIMARY KEY (`manager_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_notify_subscription_config}}',['manager_id'=>'1','action'=>'{"log_error": "1", "log_warning": "1", "behavior_error": "1", "behavior_warning": "1"}']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_notify_subscription_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

