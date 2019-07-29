<?php

use yii\db\Migration;

class m190719_024049_member_auth extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_auth}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'unionid' => "varchar(64) NULL DEFAULT '' COMMENT '唯一ID'",
            'oauth_client' => "varchar(20) NULL DEFAULT '' COMMENT '授权组别'",
            'oauth_client_user_id' => "varchar(100) NULL DEFAULT '' COMMENT '授权id'",
            'gender' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '性别[0:未知;1:男;2:女]'",
            'nickname' => "varchar(100) NULL DEFAULT '' COMMENT '昵称'",
            'head_portrait' => "varchar(150) NULL DEFAULT '' COMMENT '头像'",
            'birthday' => "date NULL COMMENT '生日'",
            'country' => "varchar(100) NULL DEFAULT '' COMMENT '国家'",
            'province' => "varchar(100) NULL DEFAULT '' COMMENT '省'",
            'city' => "varchar(100) NULL DEFAULT '' COMMENT '市'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户_第三方登录'");
        
        /* 索引设置 */
        $this->createIndex('oauth_client','{{%member_auth}}','oauth_client, oauth_client_user_id',0);
        $this->createIndex('member_id','{{%member_auth}}','member_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_auth}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

