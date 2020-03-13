<?php

use yii\db\Migration;

class m200311_030249_merchant_bank_account extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant_bank_account}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'branch_bank_name' => "varchar(50) NULL COMMENT '支行信息'",
            'realname' => "varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名'",
            'account_number' => "varchar(50) NULL DEFAULT '' COMMENT '银行账号'",
            'bank_code' => "varchar(50) NULL DEFAULT '' COMMENT '银行编号'",
            'mobile' => "varchar(20) NOT NULL DEFAULT '' COMMENT '手机号'",
            'is_default' => "int(11) NULL DEFAULT '0' COMMENT '是否默认账号'",
            'account_type' => "int(11) NULL DEFAULT '1' COMMENT '账户类型，1：银行卡，2：微信，3：支付宝'",
            'account_type_name' => "varchar(30) NULL DEFAULT '银行卡' COMMENT '账户类型名称：银行卡，微信，支付宝'",
            'ali_number' => "varchar(50) NULL DEFAULT '' COMMENT '支付宝账号'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员提现账号'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant_bank_account}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

