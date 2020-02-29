<?php

use yii\db\Migration;

/**
 * Class m190908_032821_wechat_form_id
 */
class m190908_032821_wechat_form_id extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%addon_wechat_form_id}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) NOT NULL COMMENT '用户id'",
            'form_id' => "varchar(100) NOT NULL DEFAULT '' COMMENT 'formid'",
            'stoped_at' => "int(10) unsigned DEFAULT '0' COMMENT '失效时间'",
            'created_at' => "int(10) unsigned DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        /* 索引设置 */
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_form_id}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}