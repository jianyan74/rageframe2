<?php

use yii\db\Migration;

class m190719_024049_sys_menu_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_menu_cate}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'icon' => "varchar(20) NULL DEFAULT '' COMMENT 'icon'",
            'is_default_show' => "tinyint(2) unsigned NULL DEFAULT '0' COMMENT '默认显示'",
            'is_addon' => "tinyint(2) unsigned NULL DEFAULT '0' COMMENT '默认非插件顶级分类'",
            'sort' => "int(10) NULL DEFAULT '0' COMMENT '排序'",
            'level' => "tinyint(1) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'tree' => "varchar(300) NOT NULL DEFAULT '' COMMENT '树'",
            'pid' => "int(10) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='系统_菜单分类表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_menu_cate}}',['id'=>'1','title'=>'平台首页','icon'=>'fa-bookmark','is_default_show'=>'1','is_addon'=>'0','sort'=>'0','level'=>'1','tree'=>'tr_0','pid'=>'0','status'=>'1','created_at'=>'1528042032','updated_at'=>'1563419373']);
        $this->insert('{{%sys_menu_cate}}',['id'=>'2','title'=>'微信公众号','icon'=>'fa-wechat','is_default_show'=>'0','is_addon'=>'0','sort'=>'1','level'=>'1','tree'=>'tr_0','pid'=>'0','status'=>'1','created_at'=>'1528042033','updated_at'=>'1563419396']);
        $this->insert('{{%sys_menu_cate}}',['id'=>'3','title'=>'系统管理','icon'=>'fa-cogs','is_default_show'=>'0','is_addon'=>'0','sort'=>'2','level'=>'1','tree'=>'tr_0','pid'=>'0','status'=>'1','created_at'=>'1528042096','updated_at'=>'1553833462']);
        $this->insert('{{%sys_menu_cate}}',['id'=>'4','title'=>'应用中心','icon'=>'fa-th-large','is_default_show'=>'0','is_addon'=>'1','sort'=>'999','level'=>'1','tree'=>'tr_0','pid'=>'0','status'=>'1','created_at'=>'1528042096','updated_at'=>'1553833459']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_menu_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

