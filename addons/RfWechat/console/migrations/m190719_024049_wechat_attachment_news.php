<?php

use yii\db\Migration;

class m190719_024049_wechat_attachment_news extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_attachment_news}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'attachment_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '关联的资源id'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'thumb_media_id' => "varchar(50) NULL DEFAULT '' COMMENT '图文消息的封面图片素材id（必须是永久mediaID）'",
            'thumb_url' => "varchar(200) NULL DEFAULT '' COMMENT '缩略图Url'",
            'author' => "varchar(64) NULL DEFAULT '' COMMENT '作者'",
            'digest' => "varchar(200) NULL DEFAULT '' COMMENT '简介'",
            'show_cover_pic' => "tinyint(4) NULL DEFAULT '0' COMMENT '0为false，即不显示，1为true，即显示'",
            'content' => "mediumtext NULL COMMENT '图文消息的具体内容，支持HTML标签，必须少于2万字符'",
            'content_source_url' => "varchar(200) NULL DEFAULT '' COMMENT '图文消息的原文地址，即点击“阅读原文”后的URL'",
            'media_url' => "varchar(200) NULL DEFAULT '' COMMENT '资源Url'",
            'sort' => "int(11) NULL DEFAULT '0' COMMENT '排序'",
            'year' => "int(10) unsigned NULL DEFAULT '0' COMMENT '年份'",
            'month' => "int(10) NULL DEFAULT '0' COMMENT '月份'",
            'day' => "int(10) unsigned NULL DEFAULT '0' COMMENT '日'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_文章表'");
        
        /* 索引设置 */
        $this->createIndex('attachment_id','{{%wechat_attachment_news}}','attachment_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_attachment_news}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

