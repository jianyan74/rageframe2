<?php

use yii\db\Migration;

/**
 * Class m190525_092018_sys_user
 */
class m190525_092018_sys_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%sys_employee}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'employee_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '帐号'",
            'employee_name' => "varchar(10) NOT NULL DEFAULT '' COMMENT '员工姓名'",
            'password_hash' => "varchar(150) NOT NULL DEFAULT '' COMMENT '密码'",
            'head_portrait' => "char(150) NULL DEFAULT '' COMMENT '头像'",
            'gender' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '性别[0:未知;1:男;2:女]'",
            'politics_statas'=>"varchar(10) NOT NULL DEFAULT '' COMMENT '政治面貌'",
            'marriage' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '婚姻状况[0:未婚;1:已婚;2:离婚;3:丧偶]'",
            'qq' => "varchar(20) NULL DEFAULT '' COMMENT 'qq'",
            'email' => "varchar(60) NULL DEFAULT '' COMMENT '邮箱'",
            'birthday' => "date NULL COMMENT '生日'",
            'provinces' => "int(11) NULL DEFAULT '0' COMMENT '省'",
            'city' => "int(11) NULL DEFAULT '0' COMMENT '城市'",
            'area' => "int(11) NULL DEFAULT '0' COMMENT '地区'",
            'address' => "varchar(100) NULL DEFAULT '' COMMENT '默认地址'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机号码'",
            'home_phone' => "varchar(20) NULL DEFAULT '' COMMENT '家庭号码'",
            'id_number' => "varchar(18) NOT NULL DEFAULT '' COMMENT '身份证号码'",
            'bankard' => "varchar(20) NOT NULL DEFAULT '' COMMENT '银行卡号'",

            'visit_count' => "smallint(5) unsigned NULL DEFAULT '0' COMMENT '访问次数'",
            'last_time' => "int(10) NULL DEFAULT '0' COMMENT '最后一次登陆时间'",
            'last_ip' => "varchar(16) NULL DEFAULT '' COMMENT '最后一次登陆ip'",
            'role' => "smallint(6) NULL DEFAULT '10' COMMENT '权限'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(11) unsigned NOT NULL COMMENT '创建时间'",
            'updated_at' => "int(11) unsigned NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_员工表'");

        /* 索引设置 */


        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_user}}');
        $this->execute('SET foreign_key_checks = 1;');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190525_092018_sys_user cannot be reverted.\n";

        return false;
    }
    */
}
