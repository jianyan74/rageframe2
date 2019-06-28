<?php

use yii\db\Migration;

class m190508_031059_sys_manager extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_manager}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL COMMENT '商户id'",
            'username' => "varchar(20) NOT NULL DEFAULT '' COMMENT '帐号'",
            'password_hash' => "varchar(150) NOT NULL DEFAULT '' COMMENT '密码'",
            'auth_key' => "varchar(32) NOT NULL DEFAULT '' COMMENT '授权令牌'",
            'password_reset_token' => "varchar(150) NULL DEFAULT '' COMMENT '密码重置令牌'",
            'type' => "tinyint(1) NULL DEFAULT '1' COMMENT '1:普通管理员;10超级管理员'",
            'realname' => "varchar(10) NULL DEFAULT '' COMMENT '真实姓名'",
            'head_portrait' => "char(150) NULL DEFAULT '' COMMENT '头像'",
            'gender' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '性别[0:未知;1:男;2:女]'",
            'qq' => "varchar(20) NULL DEFAULT '' COMMENT 'qq'",
            'email' => "varchar(60) NULL DEFAULT '' COMMENT '邮箱'",
            'birthday' => "date NULL COMMENT '生日'",
            'province_id' => "int(10) NULL DEFAULT '0' COMMENT '省'",
            'city_id' => "int(10) NULL DEFAULT '0' COMMENT '城市'",
            'area_id' => "int(10) NULL DEFAULT '0' COMMENT '地区'",
            'address' => "varchar(100) NULL DEFAULT '' COMMENT '默认地址'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机号码'",
            'home_phone' => "varchar(20) NULL DEFAULT '' COMMENT '家庭号码'",
            'visit_count' => "smallint(5) unsigned NULL DEFAULT '0' COMMENT '访问次数'",
            'last_time' => "int(10) NULL DEFAULT '0' COMMENT '最后一次登陆时间'",
            'last_ip' => "varchar(16) NULL DEFAULT '' COMMENT '最后一次登陆ip'",
            'role' => "smallint(6) NULL DEFAULT '10' COMMENT '权限'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_后台管理员表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_manager}}',['id'=>'1','merchant_id'=>'1','username'=>'admin','password_hash'=>'$2y$13$9EEY5iauP40/k1TYTY1E3uhud1Q7QTsM/DGA0FiWJ6dkwk01KRhF.','auth_key'=>'z6lrwixmdNF4VqtkXw6z-3vMZdSdngm2','password_reset_token'=>'','type'=>'10','realname'=>'简言','head_portrait'=>'','gender'=>'1','qq'=>'751393839','email'=>'','birthday'=>'2017-02-25','province_id'=>'330000','city_id'=>'330200','area_id'=>'330203','address'=>'大潮街道666号','mobile'=>'','home_phone'=>'','visit_count'=>'0','last_time'=>'1557281990','last_ip'=>'127.0.0.1','role'=>'10','status'=>'1','created_at'=>'1449114934','updated_at'=>'1557281990']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_manager}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

