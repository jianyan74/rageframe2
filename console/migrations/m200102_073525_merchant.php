<?php

use yii\db\Migration;

class m200102_073525_merchant extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'title' => "varchar(200) NULL DEFAULT '' COMMENT '商户名称'",
            'user_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '当前余额'",
            'accumulate_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计余额'",
            'give_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计赠送余额'",
            'consume_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '累计消费金额'",
            'frozen_money' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '冻结金额'",
            'term_of_validity_type' => "int(1) NULL DEFAULT '0' COMMENT '有效期类型 0固定时间 1不限'",
            'start_time' => "int(10) NULL DEFAULT '0' COMMENT '开始时间'",
            'end_time' => "int(10) NULL DEFAULT '0' COMMENT '结束时间'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%merchant}}',['id'=>'1','title'=>'系统默认请不要占用','user_money'=>'0.00','accumulate_money'=>'0.00','give_money'=>'0.00','consume_money'=>'0.00','frozen_money'=>'0.00','term_of_validity_type'=>'0','start_time'=>'0','end_time'=>'0','status'=>'-1','created_at'=>'1572509879','updated_at'=>'1572561725']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

