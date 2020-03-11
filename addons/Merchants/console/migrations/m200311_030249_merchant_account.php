<?php

use yii\db\Migration;

class m200311_030249_merchant_account extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant_account}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'level' => "int(11) NULL DEFAULT '-1' COMMENT '会员等级'",
            'user_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '当前余额'",
            'accumulate_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计余额'",
            'give_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计赠送余额'",
            'consume_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计消费金额'",
            'frozen_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '冻结金额'",
            'user_integral' => "int(11) NULL DEFAULT '0' COMMENT '当前积分'",
            'accumulate_integral' => "int(11) NULL DEFAULT '0' COMMENT '累计积分'",
            'give_integral' => "int(11) NULL DEFAULT '0' COMMENT '累计赠送积分'",
            'consume_integral' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计消费积分'",
            'frozen_integral' => "int(11) NULL DEFAULT '0' COMMENT '冻结积分'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员_账户统计表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant_account}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

