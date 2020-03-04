<?php

use yii\db\Migration;

class m200304_040208_common_auth_group_item extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_auth_group_item}}', [
            'group_id' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分组ID'",
            'item_id' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID'",
            'name' => "varchar(200) NOT NULL DEFAULT '' COMMENT '权限名称'",
            'app_id' => "varchar(30) NOT NULL DEFAULT '' COMMENT '应用ID'",
            'addons_name' => "varchar(200) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'is_menu' => "tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否菜单'",
            'is_addon' => "tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否插件'",
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公用-组权限授权表'");
        
        /* 索引设置 */
        $this->createIndex('group_id','{{%common_auth_group_item}}','group_id',0);
        $this->createIndex('item_id','{{%common_auth_group_item}}','item_id',0);
        
        
        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_auth_group_item}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

