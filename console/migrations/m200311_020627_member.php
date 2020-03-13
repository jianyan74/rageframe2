<?php

use yii\db\Migration;

class m200311_020627_member extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'username' => "varchar(20) NULL DEFAULT '' COMMENT '帐号'",
            'password_hash' => "varchar(150) NULL DEFAULT '' COMMENT '密码'",
            'auth_key' => "varchar(32) NULL DEFAULT '' COMMENT '授权令牌'",
            'password_reset_token' => "varchar(150) NULL DEFAULT '' COMMENT '密码重置令牌'",
            'type' => "tinyint(1) NULL DEFAULT '1' COMMENT '类别[1:普通会员;10管理员]'",
            'nickname' => "varchar(50) NULL DEFAULT '' COMMENT '昵称'",
            'realname' => "varchar(50) NULL DEFAULT '' COMMENT '真实姓名'",
            'head_portrait' => "varchar(150) NULL DEFAULT '' COMMENT '头像'",
            'current_level' => "tinyint(4) NULL DEFAULT '1' COMMENT '当前级别'",
            'gender' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '性别[0:未知;1:男;2:女]'",
            'qq' => "varchar(20) NULL DEFAULT '' COMMENT 'qq'",
            'email' => "varchar(60) NULL DEFAULT '' COMMENT '邮箱'",
            'birthday' => "date NULL COMMENT '生日'",
            'visit_count' => "int(10) unsigned NULL DEFAULT '1' COMMENT '访问次数'",
            'home_phone' => "varchar(20) NULL DEFAULT '' COMMENT '家庭号码'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机号码'",
            'role' => "smallint(6) NULL DEFAULT '10' COMMENT '权限'",
            'last_time' => "int(10) NULL DEFAULT '0' COMMENT '最后一次登录时间'",
            'last_ip' => "varchar(16) NULL DEFAULT '' COMMENT '最后一次登录ip'",
            'province_id' => "int(10) NULL DEFAULT '0' COMMENT '省'",
            'city_id' => "int(10) NULL DEFAULT '0' COMMENT '城市'",
            'area_id' => "int(10) NULL DEFAULT '0' COMMENT '地区'",
            'pid' => "int(10) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'level' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'tree' => "varchar(2000) NOT NULL DEFAULT '' COMMENT '树'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='用户_会员表'");
        
        /* 索引设置 */
        $this->createIndex('username','{{%member}}','username',0);
        $this->createIndex('mobile','{{%member}}','mobile',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

