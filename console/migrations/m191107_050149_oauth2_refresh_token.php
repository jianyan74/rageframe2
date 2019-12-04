<?php

use yii\db\Migration;

class m191107_050149_oauth2_refresh_token extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%oauth2_refresh_token}}', [
            'refresh_token' => "varchar(80) NOT NULL",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'client_id' => "varchar(64) NOT NULL",
            'member_id' => "varchar(100) NULL DEFAULT ''",
            'expires' => "timestamp NOT NULL",
            'scope' => "json NULL",
            'grant_type' => "varchar(30) NULL DEFAULT '' COMMENT '组别'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`refresh_token`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='oauth2_授权令牌'");
        
        /* 索引设置 */
        $this->createIndex('client_id','{{%oauth2_refresh_token}}','client_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%oauth2_refresh_token}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

