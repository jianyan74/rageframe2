<?php

use yii\db\Migration;

class m190508_031059_sys_action_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_action_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL COMMENT '商户id'",
            'manager_id' => "int(10) NOT NULL DEFAULT '0' COMMENT '执行用户id'",
            'behavior' => "varchar(50) NULL DEFAULT '' COMMENT '行为类别'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型'",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '模块'",
            'controller' => "varchar(50) NULL DEFAULT '' COMMENT '控制器'",
            'action' => "varchar(50) NULL DEFAULT '' COMMENT '控制器方法'",
            'url' => "varchar(200) NULL DEFAULT '' COMMENT '提交url'",
            'get_data' => "text NULL COMMENT 'get数据'",
            'post_data' => "longtext NULL COMMENT 'post数据'",
            'ip' => "varchar(16) NULL DEFAULT '' COMMENT 'ip地址'",
            'remark' => "varchar(1000) NULL DEFAULT '' COMMENT '日志备注'",
            'country' => "varchar(50) NULL DEFAULT '' COMMENT '国家'",
            'provinces' => "varchar(50) NULL DEFAULT '' COMMENT '省'",
            'city' => "varchar(50) NULL DEFAULT '' COMMENT '城市'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_行为表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_action_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

