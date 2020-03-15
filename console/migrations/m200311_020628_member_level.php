<?php

use yii\db\Migration;

class m200311_020628_member_level extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_level}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'level' => "int(11) NULL DEFAULT '0' COMMENT '等级（数字越大等级越高）'",
            'name' => "varchar(255) NULL COMMENT '等级名称'",
            'money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '消费额度满足则升级'",
            'check_money' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '选中消费额度'",
            'integral' => "int(11) NULL DEFAULT '0' COMMENT '消费积分满足则升级'",
            'check_integral' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '选中消费积分'",
            'middle' => "tinyint(1) NULL DEFAULT '0' COMMENT '条件（0或 1且）'",
            'discount' => "decimal(10,1) NULL DEFAULT '10.0' COMMENT '折扣'",
            'status' => "int(11) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'detail' => "varchar(255) NULL DEFAULT '' COMMENT '会员介绍'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='会员等级表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_level}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

