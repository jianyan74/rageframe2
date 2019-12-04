<?php

use yii\db\Migration;

class m191104_081800_addon_example_curd extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_example_curd}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'cate_id' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID(单选)'",
            'member_id' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID'",
            'sort' => "int(10) NULL DEFAULT '0' COMMENT '排序'",
            'position' => "int(11) NOT NULL DEFAULT '0' COMMENT '推荐位'",
            'sex' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '性别1男2女'",
            'content' => "text NOT NULL COMMENT '内容'",
            'tag' => "varchar(100) NOT NULL DEFAULT '' COMMENT '标签'",
            'cover' => "varchar(100) NOT NULL DEFAULT '' COMMENT '图片'",
            'covers' => "json NOT NULL COMMENT '图片组'",
            'file' => "varchar(100) NOT NULL DEFAULT '' COMMENT '文件'",
            'files' => "json NOT NULL COMMENT '文件组'",
            'attachfile' => "varchar(100) NOT NULL DEFAULT '' COMMENT '附件'",
            'keywords' => "varchar(100) NOT NULL DEFAULT '' COMMENT '关键字'",
            'description' => "varchar(200) NOT NULL DEFAULT '' COMMENT '描述'",
            'price' => "float(10,2) unsigned NOT NULL DEFAULT '0' COMMENT '价格'",
            'views' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击'",
            'start_time' => "int(10) NULL DEFAULT '0' COMMENT '开始时间'",
            'end_time' => "int(10) NULL DEFAULT '0' COMMENT '结束时间'",
            'email' => "varchar(60) NULL DEFAULT ''",
            'provinces' => "varchar(10) NULL DEFAULT ''",
            'city' => "varchar(10) NULL DEFAULT ''",
            'area' => "varchar(10) NULL DEFAULT ''",
            'ip' => "varchar(16) NULL DEFAULT '' COMMENT 'ip'",
            'date' => "varchar(20) NULL DEFAULT ''",
            'time' => "varchar(20) NULL DEFAULT ''",
            'color' => "varchar(7) NULL DEFAULT '' COMMENT '颜色'",
            'address' => "json NOT NULL COMMENT '图片组'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_示例插件_curd'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_example_curd}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

