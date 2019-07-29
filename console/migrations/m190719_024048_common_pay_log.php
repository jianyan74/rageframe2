<?php

use yii\db\Migration;

class m190719_024048_common_pay_log extends Migration
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
            'order_sn' => "varchar(20) NULL DEFAULT '' COMMENT '关联订单号'",
            'order_group' => "varchar(20) NULL DEFAULT '' COMMENT '组别[默认统一支付类型]'",
            'openid' => "varchar(50) NULL DEFAULT '' COMMENT 'openid'",
            'mch_id' => "varchar(20) NULL DEFAULT '' COMMENT '商户支付账户'",
            'out_trade_no' => "varchar(32) NULL DEFAULT '' COMMENT '商户订单号'",
            'transaction_id' => "varchar(50) NULL DEFAULT '' COMMENT '微信订单号'",
            'total_fee' => "double(10,2) NULL DEFAULT '0' COMMENT '微信充值金额'",
            'fee_type' => "varchar(10) NULL DEFAULT '' COMMENT '标价币种'",
            'pay_type' => "tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付类型[1:微信;2:支付宝;3:银联]'",
            'pay_fee' => "double(10,2) NOT NULL DEFAULT '0' COMMENT '支付金额'",
            'pay_status' => "tinyint(2) NULL DEFAULT '0' COMMENT '支付状态'",
            'pay_time' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'trade_type' => "varchar(16) NULL DEFAULT '' COMMENT '交易类型，取值为：JSAPI，NATIVE，APP等'",
            'refund_sn' => "varchar(100) NULL DEFAULT '' COMMENT '退款编号'",
            'refund_fee' => "double(10,2) NOT NULL DEFAULT '0' COMMENT '退款金额'",
            'is_refund' => "tinyint(4) NULL DEFAULT '0' COMMENT '退款情况[0:未退款;1已退款]'",
            'ip' => "varchar(30) NULL DEFAULT '' COMMENT 'ip地址'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公用_支付日志'");
        
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

