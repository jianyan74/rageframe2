<?php

use yii\db\Migration;

class m200311_030250_merchant_commission_withdraw extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant_commission_withdraw}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'withdraw_no' => "varchar(100) NULL DEFAULT '' COMMENT '提现流水号'",
            'account_type' => "int(11) NULL DEFAULT '1' COMMENT '账户类型，1：银行卡，2：微信，3：支付宝'",
            'bank_name' => "varchar(50) NULL DEFAULT '' COMMENT '提现银行名称'",
            'account_number' => "varchar(50) NULL DEFAULT '' COMMENT '提现银行账号'",
            'ali_number' => "varchar(50) NULL DEFAULT '' COMMENT '支付宝账号'",
            'realname' => "varchar(30) NULL DEFAULT '' COMMENT '提现账户姓名'",
            'mobile' => "varchar(20) NULL DEFAULT '' COMMENT '手机'",
            'cash' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '提现金额'",
            'memo' => "varchar(200) NULL DEFAULT '' COMMENT '备注'",
            'payment_date' => "int(11) NULL DEFAULT '0' COMMENT '到账日期'",
            'transfer_type' => "int(11) NULL DEFAULT '1' COMMENT '转账方式   1 线下转账  2线上转账'",
            'transfer_name' => "varchar(50) NULL DEFAULT '' COMMENT '转账银行名称'",
            'transfer_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '转账金额'",
            'transfer_status' => "int(11) NULL DEFAULT '0' COMMENT '转账状态 0未转账 1已转账 -1转账失败'",
            'transfer_remark' => "varchar(200) NULL DEFAULT '' COMMENT '转账备注'",
            'transfer_result' => "varchar(200) NULL DEFAULT '' COMMENT '转账结果'",
            'transfer_no' => "varchar(100) NULL DEFAULT '' COMMENT '转账流水号'",
            'transfer_account_no' => "varchar(100) NULL DEFAULT '' COMMENT '转账银行账号'",
            'state' => "smallint(6) NULL DEFAULT '0' COMMENT '当前状态 0已申请(等待处理) 1已同意 -1 已拒绝'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员_余额提现记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant_commission_withdraw}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

