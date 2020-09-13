<?php

use yii\db\Migration;

class m200806_080040_common_pay_refund extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_pay_refund}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(11) NULL DEFAULT '0' COMMENT '买家id'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'order_sn' => "varchar(30) NULL DEFAULT '' COMMENT '关联订单号'",
            'pay_id' => "int(11) NULL DEFAULT '0' COMMENT '支付ID'",
            'refund_trade_no' => "varchar(55) NULL DEFAULT '' COMMENT '退款交易号'",
            'refund_money' => "decimal(10,2) NULL COMMENT '退款金额'",
            'refund_way' => "int(11) NULL DEFAULT '0' COMMENT '退款方式'",
            'ip' => "varchar(30) NULL DEFAULT '' COMMENT '申请者ip'",
            'remark' => "varchar(255) NULL DEFAULT '' COMMENT '备注'",
            'created_at' => "int(10) NULL DEFAULT '0'",
            'updated_at' => "int(10) NULL DEFAULT '0'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单退款账户记录'");
        
        /* 索引设置 */
        $this->createIndex('order_sn','{{%common_pay_refund}}','order_sn',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_pay_refund}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

