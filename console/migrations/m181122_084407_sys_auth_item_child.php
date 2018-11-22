<?php

use yii\db\Migration;

class m181122_084407_sys_auth_item_child extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_auth_item_child}}', [
            'parent' => 'varchar(64) NOT NULL',
            'child' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (`parent`,`child`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_角色权限表'");
        
        /* 索引设置 */
        $this->createIndex('child','{{%sys_auth_item_child}}','child',0);
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_item_0701_00','{{%sys_auth_item_child}}', 'parent', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        $this->addForeignKey('fk_sys_auth_item_0701_01','{{%sys_auth_item_child}}', 'child', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/main/clear-cache']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/create']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/install']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/uninstall']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/edit-all']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/personal']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/up-password']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/system/info']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/system/server']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'base']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-addons-plug']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth-accredit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth-role']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth-rule']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-backups']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-config-cate']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-function']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-log']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-manager']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-menu']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-menu-cate']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-tool']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys/config']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_auth_item_child}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

