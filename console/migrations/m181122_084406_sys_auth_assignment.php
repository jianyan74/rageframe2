<?php

use yii\db\Migration;

class m181122_084406_sys_auth_assignment extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_auth_assignment}}', [
            'item_name' => 'varchar(64) NOT NULL',
            'user_id' => 'varchar(64) NOT NULL',
            'created_at' => 'int(11) NULL',
            'PRIMARY KEY (`item_name`,`user_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_角色分配表'");
        
        /* 索引设置 */
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_item_9253_00','{{%sys_auth_assignment}}', 'item_name', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        $this->insert('{{%sys_auth_assignment}}',['item_name'=>'测试','user_id'=>'5','created_at'=>'1538202825']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_auth_assignment}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

