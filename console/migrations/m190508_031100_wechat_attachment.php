<?php

use yii\db\Migration;

class m190508_031100_wechat_attachment extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_attachment}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL COMMENT '商户id'",
            'file_name' => "varchar(150) NULL DEFAULT '' COMMENT '文件原始名'",
            'local_url' => "varchar(150) NULL DEFAULT '' COMMENT '本地地址'",
            'media_type' => "varchar(15) NOT NULL DEFAULT '' COMMENT '类别'",
            'media_id' => "varchar(50) NULL DEFAULT '' COMMENT '微信资源ID'",
            'media_url' => "varchar(5000) NULL DEFAULT '' COMMENT '资源Url'",
            'width' => "int(10) unsigned NULL COMMENT '宽度'",
            'height' => "int(10) unsigned NULL COMMENT '高度'",
            'year' => "int(10) unsigned NULL COMMENT '年份'",
            'month' => "int(10) NULL DEFAULT '0' COMMENT '月份'",
            'day' => "int(10) unsigned NULL COMMENT '日'",
            'description' => "varchar(200) NULL DEFAULT '' COMMENT '视频描述'",
            'is_temporary' => "varchar(10) NULL DEFAULT '' COMMENT '类型[临时:tmp永久:perm]'",
            'link_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '1微信2本地'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信_资源表'");
        
        /* 索引设置 */
        $this->createIndex('media_id','{{%wechat_attachment}}','media_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_attachment}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

