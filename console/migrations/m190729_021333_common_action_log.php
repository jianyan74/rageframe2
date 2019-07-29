<?php

use yii\db\Migration;

class m190729_021333_common_action_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_action_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'user_id' => "int(10) NOT NULL DEFAULT '0' COMMENT '用户id'",
            'behavior' => "varchar(50) NULL DEFAULT '' COMMENT '行为类别'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型'",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '模块'",
            'controller' => "varchar(50) NULL DEFAULT '' COMMENT '控制器'",
            'action' => "varchar(50) NULL DEFAULT '' COMMENT '控制器方法'",
            'url' => "varchar(200) NULL DEFAULT '' COMMENT '提交url'",
            'get_data' => "json NULL COMMENT 'get数据'",
            'post_data' => "json NULL COMMENT 'post数据'",
            'header_data' => "json NULL COMMENT 'header数据'",
            'ip' => "varchar(16) NULL DEFAULT '' COMMENT 'ip地址'",
            'remark' => "varchar(1000) NULL DEFAULT '' COMMENT '日志备注'",
            'country' => "varchar(50) NULL DEFAULT '' COMMENT '国家'",
            'provinces' => "varchar(50) NULL DEFAULT '' COMMENT '省'",
            'city' => "varchar(50) NULL DEFAULT '' COMMENT '城市'",
            'device' => "varchar(200) NULL DEFAULT '' COMMENT '设备信息'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'",
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
        $this->dropTable('{{%common_action_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

