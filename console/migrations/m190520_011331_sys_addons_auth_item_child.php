<?php

use yii\db\Migration;

class m190520_011331_sys_addons_auth_item_child extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_addons_auth_item_child}}', [
            'parent' => "varchar(64) NOT NULL",
            'child' => "varchar(64) NOT NULL",
            'addons_name' => "varchar(30) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'type' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '1:菜单路由:2:核心入口'",
            'PRIMARY KEY (`parent`,`child`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_插件_权限授权表'");
        
        /* 索引设置 */
        $this->createIndex('child','{{%sys_addons_auth_item_child}}','parent, child',0);
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_item_1859_00','{{%sys_addons_auth_item_child}}', 'parent', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample','addons_name'=>'RfExample','type'=>'1']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:cate/ajax-edit','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:cate/ajax-update','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:cate/delete','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:cate/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:curd/ajax-update','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:curd/delete','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:curd/edit','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:curd/export','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:curd/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:elastic-search/ajax-update','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:elastic-search/delete','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:elastic-search/edit','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:elastic-search/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:excel/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:grid-curd/ajax-update','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:grid-curd/delete','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:grid-curd/edit','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:grid-curd/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:mongo-db-curd/ajax-update','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:mongo-db-curd/delete','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:mongo-db-curd/edit','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:mongo-db-curd/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:queue/index','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:rfAddonsCover','addons_name'=>'RfExample','type'=>'1']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:rfAddonsRule','addons_name'=>'RfExample','type'=>'1']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:setting/display','addons_name'=>'RfExample','type'=>'1']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:video/cut-image','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:xunsearch/ajax-update','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:xunsearch/delete','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:xunsearch/edit','addons_name'=>'RfExample','type'=>'2']);
        $this->insert('{{%sys_addons_auth_item_child}}',['parent'=>'测试','child'=>'RfExample:xunsearch/index','addons_name'=>'RfExample','type'=>'2']);
        
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

