<?php

use yii\db\Migration;

class m200311_020626_common_addons_binding extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_addons_binding}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用id'",
            'entry' => "varchar(10) NOT NULL DEFAULT '' COMMENT '入口类别[menu,cover]'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '名称'",
            'route' => "varchar(200) NOT NULL DEFAULT '' COMMENT '路由'",
            'icon' => "varchar(50) NULL DEFAULT '' COMMENT '图标'",
            'params' => "json NULL COMMENT '参数'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_插件菜单表'");
        
        /* 索引设置 */
        $this->createIndex('addons_name','{{%common_addons_binding}}','addons_name',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_addons_binding}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

