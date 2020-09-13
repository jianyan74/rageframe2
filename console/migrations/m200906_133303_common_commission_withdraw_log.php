<?php

use yii\db\Migration;

class m200906_133303_common_commission_withdraw_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_commission_withdraw_log}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'addons_name' => "varchar(100) NULL DEFAULT '' COMMENT '插件名称'",
            'withdraw_no' => "varchar(30) NULL DEFAULT '' COMMENT '关联订单号'",
            'withdraw_group' => "varchar(20) NULL DEFAULT '' COMMENT '组别[默认统一支付类型]'",
            'enc_bank_name' => "varchar(255) NULL DEFAULT '' COMMENT '银行'",
            'enc_bank_no' => "varchar(50) NULL COMMENT '卡号'",
            'enc_true_name' => "varchar(50) NULL COMMENT '真实姓名'",
            'openid' => "varchar(50) NULL DEFAULT '' COMMENT 'openid'",
            'mch_id' => "varchar(20) NULL DEFAULT '' COMMENT '商户支付账户'",
            'body' => "varchar(100) NULL DEFAULT '' COMMENT '支付内容'",
            'detail' => "varchar(100) NULL DEFAULT '' COMMENT '支付详情'",
            'out_trade_no' => "varchar(32) NULL DEFAULT '' COMMENT '商户订单号'",
            'transaction_id' => "varchar(50) NULL DEFAULT '' COMMENT '微信订单号'",
            'total_fee' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '微信充值金额'",
            'pay_type' => "tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付类型[1:微信;2:支付宝;3:银联]'",
            'pay_fee' => "decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额'",
            'pay_status' => "tinyint(2) NULL DEFAULT '0' COMMENT '支付状态'",
            'pay_time' => "int(10) NULL DEFAULT '0' COMMENT '支付时间'",
            'trade_type' => "varchar(16) NULL DEFAULT '' COMMENT '交易类型'",
            'create_ip' => "varchar(30) NULL DEFAULT '' COMMENT '创建者ip'",
            'pay_ip' => "varchar(30) NULL DEFAULT '' COMMENT '支付者ip'",
            'notify_url' => "varchar(100) NULL DEFAULT '' COMMENT '支付通知回调地址'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='公用_提现记录'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_commission_withdraw_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

