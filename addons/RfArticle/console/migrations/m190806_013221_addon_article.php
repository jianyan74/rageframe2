<?php

use yii\db\Migration;

class m190806_013221_addon_article extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_article}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'title' => "varchar(50) NOT NULL COMMENT '标题'",
            'cover' => "varchar(100) NULL DEFAULT '' COMMENT '封面'",
            'seo_key' => "varchar(50) NULL DEFAULT '' COMMENT 'seo关键字'",
            'seo_content' => "varchar(1000) NULL DEFAULT '' COMMENT 'seo内容'",
            'cate_id' => "int(10) NULL DEFAULT '0' COMMENT '分类id'",
            'description' => "char(140) NULL DEFAULT '' COMMENT '描述'",
            'position' => "smallint(5) NOT NULL DEFAULT '0' COMMENT '推荐位'",
            'content' => "longtext NULL COMMENT '文章内容'",
            'link' => "varchar(100) NULL DEFAULT '' COMMENT '外链'",
            'author' => "varchar(40) NULL DEFAULT '' COMMENT '作者'",
            'view' => "int(10) NOT NULL DEFAULT '0' COMMENT '浏览量'",
            'sort' => "int(10) NOT NULL DEFAULT '0' COMMENT '优先级'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章表'");
        
        /* 索引设置 */
        $this->createIndex('article_id','{{%addon_article}}','id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_article}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

