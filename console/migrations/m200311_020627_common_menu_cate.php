<?php

use yii\db\Migration;

class m200311_020627_common_menu_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_menu_cate}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'icon' => "varchar(20) NULL DEFAULT '' COMMENT 'icon'",
            'is_default_show' => "tinyint(2) unsigned NULL DEFAULT '0' COMMENT '默认显示'",
            'is_addon' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addon_centre' => "tinyint(1) NULL DEFAULT '0' COMMENT '应用中心'",
            'sort' => "int(10) NULL DEFAULT '999' COMMENT '排序'",
            'level' => "tinyint(1) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'tree' => "varchar(300) NOT NULL DEFAULT '' COMMENT '树'",
            'pid' => "int(10) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_菜单分类表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_menu_cate}}',['id'=>'1','title'=>'平台首页','app_id'=>'backend','addons_name'=>'','icon'=>'fa-bookmark','is_default_show'=>'1','is_addon'=>'0','addon_centre'=>'0','sort'=>'0','level'=>'1','tree'=>'tr_0 ','pid'=>'0','status'=>'1','created_at'=>'1572328102','updated_at'=>'1572946211']);
        $this->insert('{{%common_menu_cate}}',['id'=>'2','title'=>'系统管理','app_id'=>'backend','addons_name'=>'','icon'=>'fa-cogs','is_default_show'=>'0','is_addon'=>'0','addon_centre'=>'0','sort'=>'1000','level'=>'1','tree'=>'tr_0 ','pid'=>'0','status'=>'1','created_at'=>'1572328124','updated_at'=>'1572513083']);
        $this->insert('{{%common_menu_cate}}',['id'=>'3','title'=>'应用中心','app_id'=>'backend','addons_name'=>'','icon'=>'fa-th-large','is_default_show'=>'0','is_addon'=>'0','addon_centre'=>'1','sort'=>'1001','level'=>'1','tree'=>'tr_0 ','pid'=>'0','status'=>'1','created_at'=>'1572328141','updated_at'=>'1572513081']);
        $this->insert('{{%common_menu_cate}}',['id'=>'4','title'=>'应用中心','app_id'=>'merchant','addons_name'=>'','icon'=>'fa-th-large','is_default_show'=>'0','is_addon'=>'0','addon_centre'=>'1','sort'=>'1001','level'=>'1','tree'=>'tr_0 ','pid'=>'0','status'=>'1','created_at'=>'1572508332','updated_at'=>'1572508332']);
        $this->insert('{{%common_menu_cate}}',['id'=>'5','title'=>'平台首页','app_id'=>'merchant','addons_name'=>'','icon'=>'fa-bookmark','is_default_show'=>'1','is_addon'=>'0','addon_centre'=>'0','sort'=>'0','level'=>'1','tree'=>'tr_0 ','pid'=>'0','status'=>'1','created_at'=>'1577672798','updated_at'=>'1577698651']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_menu_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

