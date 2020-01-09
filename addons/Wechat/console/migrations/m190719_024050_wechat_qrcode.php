<?php

use yii\db\Migration;

class m190719_024050_wechat_qrcode extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_qrcode}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'name' => "varchar(50) NULL DEFAULT '' COMMENT '场景名称'",
            'keyword' => "varchar(100) NULL DEFAULT '' COMMENT '关联关键字'",
            'scene_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '场景ID'",
            'scene_str' => "varchar(64) NULL DEFAULT '' COMMENT '场景值'",
            'model' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '类型'",
            'ticket' => "varchar(250) NULL DEFAULT '' COMMENT 'ticket'",
            'expire_seconds' => "int(10) unsigned NULL DEFAULT '2592000' COMMENT '有效期'",
            'subnum' => "int(10) unsigned NULL DEFAULT '0' COMMENT '扫描次数'",
            'type' => "varchar(10) NULL DEFAULT '' COMMENT '二维码类型'",
            'extra' => "int(10) unsigned NULL DEFAULT '0'",
            'url' => "varchar(80) NULL DEFAULT '' COMMENT 'url'",
            'end_time' => "int(10) NULL DEFAULT '0'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_二维码表'");
        
        /* 索引设置 */
        $this->createIndex('idx_qrcid','{{%addon_wechat_qrcode}}','scene_id',0);
        $this->createIndex('ticket','{{%addon_wechat_qrcode}}','ticket',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_qrcode}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

