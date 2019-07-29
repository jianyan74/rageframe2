<?php

use yii\db\Migration;

class m190719_024049_member_money_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_money_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0'",
            'member_id' => "int(11) unsigned NULL COMMENT '用户id'",
            'pay_type' => "tinyint(4) NULL DEFAULT '0' COMMENT '支付类型'",
            'credit_group' => "varchar(30) NULL DEFAULT '' COMMENT '变动的组别'",
            'credit_group_detail' => "varchar(30) NULL DEFAULT '' COMMENT '变动的详细组别'",
            'num' => "double NULL DEFAULT '0' COMMENT '变动的数据'",
            'remark' => "varchar(200) NULL DEFAULT '' COMMENT '备注'",
            'ip' => "varchar(20) NULL DEFAULT '' COMMENT 'ip地址'",
            'map_id' => "int(10) NULL DEFAULT '0' COMMENT '关联id'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员_金额日志流水表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_money_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

