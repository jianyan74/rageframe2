<?php

use yii\db\Migration;

class m190806_013221_addon_article_tag extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_article_tag}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'title' => "varchar(20) NOT NULL DEFAULT '' COMMENT '标题'",
            'sort' => "int(10) NULL DEFAULT '0' COMMENT '排序'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章标签表'");
        
        /* 索引设置 */
        $this->createIndex('tag_id','{{%addon_article_tag}}','id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_article_tag}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

