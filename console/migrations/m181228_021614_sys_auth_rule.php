<?php

use yii\db\Migration;

class m181228_021614_sys_auth_rule extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_auth_rule}}', [
            'name' => 'varchar(64) NOT NULL',
            'data' => 'text NULL',
            'created_at' => 'int(11) NULL',
            'updated_at' => 'int(11) NULL',
            'PRIMARY KEY (`name`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_权限规则表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_auth_rule}}',['name'=>'管理员规则','data'=>'O:36:"backend\modules\sys\rbac\ManagerRule":3:{s:4:"name";s:7:"manager";s:9:"createdAt";N;s:9:"updatedAt";N;}','created_at'=>'1545371906','updated_at'=>'1545371916']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_auth_rule}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

