<?php

use yii\db\Migration;

class m200311_020627_common_pay_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_pay_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'addons_name' => "varchar(100) NULL DEFAULT '' COMMENT '插件名称'",
            'order_sn' => "varchar(20) NULL DEFAULT '' COMMENT '关联订单号'",
            'order_group' => "varchar(20) NULL DEFAULT '' COMMENT '组别[默认统一支付类型]'",
            'openid' => "varchar(50) NULL DEFAULT '' COMMENT 'openid'",
            'mch_id' => "varchar(20) NULL DEFAULT '' COMMENT '商户支付账户'",
            'body' => "varchar(100) NULL DEFAULT '' COMMENT '支付内容'",
            'detail' => "varchar(100) NULL DEFAULT '' COMMENT '支付详情'",
            'auth_code' => "varchar(50) NULL DEFAULT '' COMMENT '刷卡码'",
            'out_trade_no' => "varchar(32) NULL DEFAULT '' COMMENT '商户订单号'",
            'transaction_id' => "varchar(50) NULL DEFAULT '' COMMENT '微信订单号'",
            'total_fee' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '微信充值金额'",
            'fee_type' => "varchar(10) NULL DEFAULT '' COMMENT '标价币种'",
            'pay_type' => "tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付类型[1:微信;2:支付宝;3:银联]'",
            'pay_fee' => "decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额'",
            'pay_status' => "tinyint(2) NULL DEFAULT '0' COMMENT '支付状态'",
            'pay_time' => "int(10) NULL DEFAULT '0' COMMENT '支付时间'",
            'trade_type' => "varchar(16) NULL DEFAULT '' COMMENT '交易类型'",
            'refund_sn' => "varchar(100) NULL DEFAULT '' COMMENT '退款编号'",
            'refund_fee' => "decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额'",
            'is_refund' => "tinyint(4) NULL DEFAULT '0' COMMENT '退款情况[0:未退款;1已退款]'",
            'create_ip' => "varchar(30) NULL DEFAULT '' COMMENT '创建者ip'",
            'pay_ip' => "varchar(30) NULL DEFAULT '' COMMENT '支付者ip'",
            'notify_url' => "varchar(100) NULL DEFAULT '' COMMENT '支付通知回调地址'",
            'return_url' => "varchar(100) NULL DEFAULT '' COMMENT '买家付款成功跳转地址'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_支付日志'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_pay_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

