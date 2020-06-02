<?php

use yii\db\Migration;

class m200529_152443_common_action_behavior extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_action_behavior}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'url' => "varchar(200) NULL DEFAULT '' COMMENT '提交url'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型 *为不限'",
            'behavior' => "varchar(50) NULL DEFAULT '' COMMENT '行为类别'",
            'action' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '前置/后置'",
            'level' => "varchar(20) NULL DEFAULT '' COMMENT '级别'",
            'is_record_post' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '是否记录post[0;否;1是]'",
            'is_ajax' => "tinyint(4) unsigned NULL DEFAULT '2' COMMENT '是否ajax请求[1;否;2是;3不限]'",
            'remark' => "varchar(100) NULL DEFAULT '' COMMENT '备注'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'is_addon' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_行为表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_action_behavior}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

