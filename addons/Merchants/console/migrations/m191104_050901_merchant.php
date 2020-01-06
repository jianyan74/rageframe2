<?php

use yii\db\Migration;

class m191104_050901_merchant extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'title' => "varchar(200) NULL DEFAULT '' COMMENT '商户名称'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%merchant}}',['id'=>'1','title'=>'系统默认请不要占用','status'=>'-1','created_at'=>'1572509879','updated_at'=>'1572561725']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

