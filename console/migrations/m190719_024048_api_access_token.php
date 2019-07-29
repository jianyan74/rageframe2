<?php

use yii\db\Migration;

class m190719_024048_api_access_token extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%api_access_token}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'refresh_token' => "varchar(60) NULL DEFAULT '' COMMENT '刷新令牌'",
            'access_token' => "varchar(60) NULL DEFAULT '' COMMENT '授权令牌'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'group' => "varchar(30) NULL DEFAULT '' COMMENT '组别'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='api_授权秘钥表'");
        
        /* 索引设置 */
        $this->createIndex('access_token','{{%api_access_token}}','access_token',1);
        $this->createIndex('refresh_token','{{%api_access_token}}','refresh_token',1);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%api_access_token}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

