<?php

use yii\db\Migration;

class m190902_021313_member_account extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_account}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'user_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '余额'",
            'accumulate_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累积余额'",
            'frozen_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '冻结金额'",
            'user_integral' => "int(11) NULL DEFAULT '0' COMMENT '当前积分'",
            'accumulate_integral' => "int(11) NULL DEFAULT '0' COMMENT '消费积分'",
            'frozen_integral' => "int(11) NULL DEFAULT '0' COMMENT '冻结积分'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员_账户统计表'");
        
        /* 索引设置 */
        $this->createIndex('member_id','{{%member_account}}','member_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_account}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

