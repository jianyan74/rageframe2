<?php

use yii\db\Migration;

class m200311_020627_common_sms_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_sms_log}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机号码'",
            'code' => "varchar(6) NULL DEFAULT '' COMMENT '验证码'",
            'content' => "varchar(500) NULL DEFAULT '' COMMENT '内容'",
            'error_code' => "int(10) NULL DEFAULT '0' COMMENT '报错code'",
            'error_msg' => "varchar(200) NULL DEFAULT '' COMMENT '报错信息'",
            'error_data' => "longtext NULL COMMENT '报错日志'",
            'usage' => "varchar(20) NULL DEFAULT '' COMMENT '用途'",
            'used' => "tinyint(1) NULL DEFAULT '0' COMMENT '是否使用[0:未使用;1:已使用]'",
            'use_time' => "int(10) NULL DEFAULT '0' COMMENT '使用时间'",
            'ip' => "varchar(30) NULL DEFAULT '' COMMENT 'ip地址'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_短信发送日志'");
        
        /* 索引设置 */
        $this->createIndex('error_code','{{%common_sms_log}}','error_code',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_sms_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

