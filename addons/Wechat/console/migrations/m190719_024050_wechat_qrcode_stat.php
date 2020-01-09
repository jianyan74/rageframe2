<?php

use yii\db\Migration;

class m190719_024050_wechat_qrcode_stat extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_qrcode_stat}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'qrcord_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '二维码id'",
            'openid' => "varchar(50) NULL DEFAULT '' COMMENT '微信openid'",
            'type' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '1:关注;2:扫描'",
            'name' => "varchar(50) NULL DEFAULT '' COMMENT '场景名称'",
            'scene_str' => "varchar(64) NULL DEFAULT '' COMMENT '场景值'",
            'scene_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '场景ID'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_二维码扫描记录表'");
        
        /* 索引设置 */
        $this->createIndex('qrcord_id','{{%addon_wechat_qrcode_stat}}','qrcord_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_qrcode_stat}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

