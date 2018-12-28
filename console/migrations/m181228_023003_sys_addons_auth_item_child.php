<?php

use yii\db\Migration;

class m181228_023003_sys_addons_auth_item_child extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_addons_auth_item_child}}', [
            'parent' => 'varchar(64) NOT NULL',
            'child' => 'varchar(64) NOT NULL',
            'addons_name' => 'varchar(30) NOT NULL DEFAULT \'\' COMMENT \'插件名称\'',
            'PRIMARY KEY (`parent`,`child`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_插件_权限授权表'");
        
        /* 索引设置 */
        $this->createIndex('child','{{%sys_addons_auth_item_child}}','parent, child',0);
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_item_7545_00','{{%sys_addons_auth_item_child}}', 'parent', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_addons_auth_item_child}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

