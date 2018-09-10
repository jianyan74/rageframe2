<?php

use yii\db\Migration;

class m180910_013517_sys_auth_item extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_auth_item}}', [
            'name' => 'varchar(64) NOT NULL',
            'type' => 'int(11) NOT NULL',
            'key' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'唯一key\'',
            'description' => 'text NULL',
            'rule_name' => 'varchar(64) NULL DEFAULT \'\' COMMENT \'规则名称\'',
            'data' => 'text NULL',
            'parent_key' => 'int(10) NULL DEFAULT \'0\' COMMENT \'父级key\'',
            'level' => 'int(5) NULL DEFAULT \'1\' COMMENT \'级别\'',
            'sort' => 'int(10) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'created_at' => 'int(11) NULL DEFAULT \'0\'',
            'updated_at' => 'int(11) NULL DEFAULT \'0\'',
            'PRIMARY KEY (`name`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统_角色路由表'");
        
        /* 索引设置 */
        $this->createIndex('rule_name','{{%sys_auth_item}}','rule_name',0);
        $this->createIndex('type','{{%sys_auth_item}}','type',0);
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_rule_6271_00','{{%sys_auth_item}}', 'rule_name', '{{%sys_auth_rule}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_auth_item}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

