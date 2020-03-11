<?php

use yii\db\Migration;

class m200311_030250_merchant_credits_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant_credits_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'pay_type' => "tinyint(4) NULL DEFAULT '0' COMMENT '支付类型'",
            'credit_type' => "varchar(30) NOT NULL DEFAULT '' COMMENT '变动类型[integral:积分;money:余额]'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'credit_group' => "varchar(30) NULL DEFAULT '' COMMENT '变动的组别'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'old_num' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '之前的数据'",
            'new_num' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '变动后的数据'",
            'num' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '变动的数据'",
            'remark' => "varchar(200) NULL DEFAULT '' COMMENT '备注'",
            'ip' => "varchar(30) NULL DEFAULT '' COMMENT 'ip地址'",
            'map_id' => "int(10) NULL DEFAULT '0' COMMENT '关联id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员_积分等变动表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant_credits_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

